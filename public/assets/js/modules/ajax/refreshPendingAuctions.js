// Polyfill functionality - async, await
import "core-js/stable";
import "regenerator-runtime/runtime";

import axios from "axios"
import { $exists } from "../utils/jQueryElementUtils"
import { AJAX_RELOAD_INTERVAL } from "./ajaxConfig"
import { PendingAuctionComponent, PendingAuctionComponentParams } from "../components/Auction/PendingAuctionComponent"


/** @const Related ajax endpoint */
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
    if ($exists($PENDING_AUCTIONS_WRAPPER))
    {
        setInterval(refreshPendingAuctionComponents, AJAX_RELOAD_INTERVAL)
    }
}


/**
 * @brief Fetch all pending auctions available at ajax endpoint
 * @returns {Promise<*>}
 */
async function fetchPendingAuctions()
{
    const pendingAuctions = await axios(PENDING_AUCTIONS_ENDPOINT)

    return pendingAuctions.data.data
}


/**
 * @brief Filter out auctions already present in the pending-auction-wrapper
 * @param pendingAuctions
 */
function filterExistingPendingAuctions(pendingAuctions) {
    // Get existing auctions' collection
    const existingAuctions = Array.from($(".pending-auction-component"))

    // Extract their IDs
    const existingAuctionsIDs = existingAuctions.map(auction => parseInt(auction.dataset.id))

    // Filter out the already existing auctions by ID
    const newPendingAuctions = pendingAuctions.filter(
        auction => !existingAuctionsIDs.includes(auction.id)
    )

    return newPendingAuctions
}


/**
 * @brief Wrapper for instantiating new PendingAuctionComponents from an array
 * @param newPendingAuctions
 * @returns {*}
 */
function prepareAuctionComponents(newPendingAuctions) {
    return newPendingAuctions.reduce(
        (accumulatedAuctions, newAuction) => { // Build new array from
            return [
                ...accumulatedAuctions,
                // Instantiate new AuctionComponent
                PendingAuctionComponent.fromDbAuctionRecord(newAuction)
            ]
        }, []
    );
}


/**
 * @brief Prepend all new pending auctions to the top of the pending auction wrapper
 * @param pendingAuctionComponents
 */
function addPendingAuctionComponentsIntoView(pendingAuctionComponents)
{
    for (const pendingAuctionComponent of pendingAuctionComponents)
    {
        // Final integrity/duplicity check
        const duplicitAuctionComponent = $(`.auction-component[data-id=${pendingAuctionComponent.params.id}]`)
        if (!$exists(duplicitAuctionComponent))
        {
            // Add into view
            $PENDING_AUCTIONS_WRAPPER.prepend(
                pendingAuctionComponent.render()
            )
        }
    }
}


/**
 * @brief Refresh pending auctions present on current view (add new, remove deleted)
 * @returns {Promise<void>}
 */
async function refreshPendingAuctionComponents()
{
    // Fetch all pending auctions
    const pendingAuctions = await fetchPendingAuctions()
    const newPendingAuctions = filterExistingPendingAuctions(pendingAuctions);

    // TODO EXTRACT TO ADD_NEW

    // Instantiate PendingAuctionComponents from all the new pending auctions
    const newPendingAuctionComponents = prepareAuctionComponents(
        Object.values(newPendingAuctions)
    )

    // Finally add new pending auction componets to view
    addPendingAuctionComponentsIntoView(newPendingAuctionComponents)



    // TODO CODE REMOVE_DELETED
}
