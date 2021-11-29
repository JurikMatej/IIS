import { AuctionSetWinnerComponent } from "../../../components/Auction/AuctionSetWinnerComponent"
import { registerStandardComponentUpdate }
    from "../refreshUtils"

/**
 * @const Related ajax endpoint
 * @todo UN-HARDCODE
 */
const WINNING_BID_ENDPOINT = "http://localhost:8080/ajax/auctions/{id}/winning_bid"

/** @const View's winner set component wrapper */
const $SET_WINNER_WRAPPER = $(".auction-winner-set-wrapper")


/**
 * @brief Register an function that runs WinnerSetComponet's update
 *        every ${AJAX_RELOAD_INTERVAL}ms
 * @return {void}
 */
export function registerWinnerSetComponentUpdate()
{
    // Determine whether a page with a related auction is rendered
    const auctionDetailViewRendered = (document.location.href.search(/auctions\/[0-9]+/) !== -1)
    if (!auctionDetailViewRendered) return // Do not register ajax refresher for irrelevant page

    // Get id of the auction the winner is to be set for
    const splitLocationHref = document.location.href.split("/")
    const auctionID = splitLocationHref[splitLocationHref.indexOf('auctions') + 1]
    // Expand endpoint variables to provide refresh function a valid endpoint
    const expanded_endpoint = WINNING_BID_ENDPOINT.replace("{id}", auctionID)

    registerStandardComponentUpdate(
        $SET_WINNER_WRAPPER,
        [AuctionSetWinnerComponent, $SET_WINNER_WRAPPER, expanded_endpoint]
    );
}
