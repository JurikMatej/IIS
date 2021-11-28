import { AuctionPendingUserComponent } from "../../../components/Auction/AuctionPendingUserComponent"
import { registerStandardComponentsRefresh }
    from "../refreshUtils"

/**
 * @const Related ajax endpoint
 * @todo UN-HARDCODE
 */
const AUCTION_PENDING_USERS_ENDPOINT = "http://localhost:8080/ajax/auctions/{id}/pending_users"

/** @const View's auction pending users wrapper */
const $AUCTIONS_PENDING_USER_WRAPPER = $(".auction-pending-users-wrapper")


/**
 * @brief Register an function that runs AuctionPendingUserComponents' refresh
 *        every ${AJAX_RELOAD_INTERVAL}ms
 * @return {void}
 */
export function registerAuctionPendingUserComponentsRefresh()
{
    // Determine whether a page with a related auction is rendered
    const auctionDetailViewRendered = (document.location.href.search(/auctions\/[0-9]+/) !== -1)
    if (!auctionDetailViewRendered) return // Do not register ajax refresher for irrelevant page

    // Get id of the auction the user is pending for approval on
    const splitLocationHref = document.location.href.split("/")
    const auctionID = splitLocationHref[splitLocationHref.indexOf('auctions') + 1]
    // Expand endpoint variables to provide refresh function a valid endpoint
    const expanded_endpoint = AUCTION_PENDING_USERS_ENDPOINT.replace("{id}", auctionID)

    registerStandardComponentsRefresh(
        $AUCTIONS_PENDING_USER_WRAPPER,
        [AuctionPendingUserComponent, $AUCTIONS_PENDING_USER_WRAPPER, expanded_endpoint]
    );
}
