<?php


namespace App\Application\Actions\Auction;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class ApproveUserInAuctionAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];

        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            header("Location: http://$name$port$dest");
            exit();
        }

        $bidId = (int) $this->resolveArg('bidId');
        $bid = $this->bidRepository->findBidOfId($bidId);
        $auctionId = $bid->getAuctionId();
        $auction = $this->auctionRepository->findAuctionOfId($auctionId);
        
        $user = $this->userRepository->findUserOfId($_SESSION['id']);
        $user_role = $user->getRole();

        // users of auction can be managed only by right approver or admin
        if ($auction->getApproverId() !== $_SESSION['id'] || ($user_role !== "Admin" && $user_role !== "Auctioneer"))
        {
            $dest = "/unauthorized_access_error";
            header("Location: http://$name$port$dest");
            exit();
        }
        
        $bid->setAwaitingApproval(0);
        $this->bidRepository->save($bid);

        $this->auctionViewRenderer->setLayout("index.php");
        $this->auctionViewRenderer->render($this->response,"reject_approve.php", ["auctionId" => $auctionId]);
        
        return $this->response;
    }
}