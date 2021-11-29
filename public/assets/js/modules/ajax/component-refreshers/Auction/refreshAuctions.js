/**
 * @file refreshAuctions.js
 * @brief ApprovedAuctionComponents' refresh functionality
 * @note APPROVED AuctionComponents only !!!
 *
 * @todo refactor to refreshApprovedAuction
 */

import { AuctionComponent } from "../../../components/Auction/AuctionComponent"
import { registerStandardComponentsRefresh }
    from "../refreshUtils"

/** @const Current browser location url */
const LOCATION = document.location.href

/** @const Computed host url */
const HOST = LOCATION.split('/')[2]

/**
 * @const Related ajax endpoint
 */
const AUCTIONS_ENDPOINT = `/ajax/auctions/approved`

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
