/**
 * @file refreshAuctions.js
 * @brief ApprovedAuctionComponents' refresh functionality
 * @note APPROVED AuctionComponents only !!!
 */

// Polyfill functionality - async, await
import "core-js/stable";
import "regenerator-runtime/runtime";

import axios from "axios"
import { $exists } from "../utils/jQueryElementUtils"
import { AJAX_RELOAD_INTERVAL } from "./ajaxConfig"
import { AuctionComponent, AuctionComponentParams } from "../components/Auction/AuctionComponent"


/** @const Related ajax endpoint */
const AUCTIONS_ENDPOINT = "http://localhost:8080/ajax/auctions/approved"

/** @const View's approved auctions wrapper */
const $AUCTIONS_WRAPPER = $(".auctions-wrapper")


/**
 * @brief Register an function that runs AuctionComponents' refresh every ${AJAX_RELOAD_INTERVAL}ms
 * @return {void}
 */
export function registerAuctionComponentsRefresh()
{
    if ($exists($AUCTIONS_WRAPPER))
    {
        setInterval(refreshAuctionComponents, AJAX_RELOAD_INTERVAL)
    }
}


/**
 * @brief Fetch all approved auctions available at ajax endpoint
 * @returns {Promise<*>}
 */
async function fetchApprovedAuctions()
{
    const approvedAuctions = await axios(AUCTIONS_ENDPOINT)

    return approvedAuctions.data.data
}


/**
 * @brief Filter out auctions already present in the auction-wrapper
 * @param approvedAuctions
 * @return {*}
 */
function filterExistingApprovedAuctions(approvedAuctions) {
    // Get existing auctions' collection
    const existingAuctions = Array.from($(".auction-component"))

    // Extract their IDs
    const existingAuctionsIDs = existingAuctions.map(auction => parseInt(auction.dataset.id))

    // Filter out the already existing auctions by ID
    const newApprovedAuctions = approvedAuctions.filter(
        auction => !existingAuctionsIDs.includes(auction.id)
    )

    return newApprovedAuctions
}


/**
 * @brief Wrapper for instantiating new AuctionComponents from an array
 * @param newApprovedAuctions
 * @returns {*}
 */
function prepareAuctionComponents(newApprovedAuctions) {
    return newApprovedAuctions.reduce(
        (accumulatedAuctions, newAuction) => { // Build new array from
            return [
                ...accumulatedAuctions,
                // Instantiate new AuctionComponent
                AuctionComponent.fromDbAuctionRecord(newAuction)
            ]
        }, []
    );
}


/**
 * @brief Prepend all new auctions to the top of the auction wrapper
 * @param auctionComponents
 */
function addAuctionComponentsIntoView(auctionComponents)
{
    for (const auctionComponent of auctionComponents)
    {
        // Final integrity/duplicity check
        const duplicitAuctionComponent = $(`.auction-component[data-id=${auctionComponent.params.id}]`)
        if (!$exists(duplicitAuctionComponent))
        {
            // Add into view
            $AUCTIONS_WRAPPER.prepend(
                auctionComponent.render()
            )
        }
    }
}


/**
 * @brief Refresh auctions present on current view (add new, remove deleted)
 * @returns {Promise<void>}
 */
async function refreshAuctionComponents()
{
    // Fetch all approved auctions
    const approvedAuctions = await fetchApprovedAuctions()
    const newApprovedAuctions = filterExistingApprovedAuctions(approvedAuctions);

    // TODO EXTRACT TO ADD_NEW

    // Instantiate AuctionComponents from all the new approved auctions
    const newApprovedAuctionComponents = prepareAuctionComponents(
        Object.values(newApprovedAuctions)
    )

    // Finally add new auction componets to view
    addAuctionComponentsIntoView(newApprovedAuctionComponents)



    // TODO CODE REMOVE_DELETED
}
