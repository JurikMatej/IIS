/**
 * @file refreshAuctions.js
 * @brief ApprovedAuctionComponents' refresh functionality
 * @note APPROVED AuctionComponents only !!!
 */

import { AuctionComponent } from "../../../components/Auction/AuctionComponent"
import { registerStandardComponentsRefresh }
    from "../refreshUtils"

/**
 * @const Related ajax endpoint
 * @todo UN-HARDCODE
 */
const AUCTIONS_ENDPOINT = "http://localhost:8080/ajax/auctions/approved"

/** @const View's approved auctions wrapper */
const $AUCTIONS_WRAPPER = $(".auctions-wrapper")


/**
 * @brief Register an function that runs AuctionComponents' refresh every ${AJAX_RELOAD_INTERVAL}ms
 * @return {void}
 */
export function registerAuctionComponentsRefresh()
{
    registerStandardComponentsRefresh(
        $AUCTIONS_WRAPPER,
        [AuctionComponent, $AUCTIONS_WRAPPER, AUCTIONS_ENDPOINT]
    );
}
