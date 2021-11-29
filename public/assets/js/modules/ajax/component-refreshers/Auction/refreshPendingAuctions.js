import { PendingAuctionComponent } from "../../../components/Auction/PendingAuctionComponent"
import { registerStandardComponentsRefresh }
    from "../refreshUtils"

/** @const Current browser location url */
const LOCATION = document.location.href

/** @const Computed host url */
const HOST = LOCATION.split('/')[2]

/**
 * @const Related ajax endpoint
 */
const PENDING_AUCTIONS_ENDPOINT = `/ajax/auctions/pending`

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
