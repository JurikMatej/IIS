import { PendingAuctionComponent } from "../../../components/Auction/PendingAuctionComponent"
import { registerStandardComponentsRefresh }
    from "../refreshUtils"

/**
 * @const Related ajax endpoint
 * @todo UN-HARDCODE
 */
const PENDING_AUCTIONS_ENDPOINT = "http://localhost:8080/ajax/auctions/pending"

/** @const View's pending auctions wrapper */
const $PENDING_AUCTIONS_WRAPPER = $(".pending-auctions-wrapper")


/**
 * @brief Register an function that runs PendingAuctionComponents' refresh
 *        every ${AJAX_RELOAD_INTERVAL}ms
 * @return {void}
 */
export function registerPendingAuctionComponentsRefresh()
{
    registerStandardComponentsRefresh(
        $PENDING_AUCTIONS_WRAPPER,
        [PendingAuctionComponent, $PENDING_AUCTIONS_WRAPPER, PENDING_AUCTIONS_ENDPOINT]
    );
}
