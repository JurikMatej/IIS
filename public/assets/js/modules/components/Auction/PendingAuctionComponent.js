import { createComponent, getImageAssetsPath } from "../componentUtils";
import { getSecondsFromTimeString, getTimeComponentsFromTimeString, getMilisecondsFromTimeString,
    formatDateTime, dateAddTime }
    from "../../utils/dateTimeUtils"


/**
 * @brief PendingAuctionComponent construction params
 * @type {{author: string, name: string, ruleset: string, id: string, photosArray: string, detailLink: string, type: string, startDate: string}}
 */
export const PendingAuctionComponentParams = {
    id: "",
    name: "",
    detailLink: "",
    photosArray: "",
    startDate: "",
    type: "",
    ruleset: "",
    author: ""
}


/**
 * @brief Component representing an auction
 */
export class PendingAuctionComponent
{
    constructor(params) // PendingAuctionComponentParams
    {
        // Set instance fields
        this.params = params
        this.auctionStartDate = params.startDate

        // Compute needed properties
        const computedIMG = this.#computeImgTagsHtml(params.photosArray)

        // Create the component
        const componentHtml = `
            <div class="pending-auction-component" data-id="${params.id}">                        
                <h3>
                    <a class="auction-detail-link" href="${params.detailLink}">${params.name}</a>
                </h3>
                
                ${computedIMG}
                
                <h2 class="auction-date">Created: ${formatDateTime(params.startDate)}</h2>
                <p class="auction-type">Type: ${params.type}, <span class="auction-ruleset">${params.ruleset}</span></p>
                <p class="auction-author">Author: ${params.author}</p>
                
                <hr>
            </div>`


        this.html = createComponent(componentHtml);
    }


    /**
     * @brief Get instance html property
     * @returns {HTMLElement}
     */
    render()
    {
        return this.html
    }


    /**
     * @brief Create a new PendingAuctionComponent from an PendingAuctionRecord
     * @param pendingAuctionRecord
     * @returns {PendingAuctionComponent}
     */
    static fromDbPendingAuctionRecord(pendingAuctionRecord)
    {
        const pendingAuctionCompomentParams = Object.assign(PendingAuctionComponentParams);

        // Setup PendingAuctionComponentParams params
        pendingAuctionCompomentParams.id = pendingAuctionRecord.id
        pendingAuctionCompomentParams.name = pendingAuctionRecord.name
        pendingAuctionCompomentParams.detailLink = `/auctions/waiting/${pendingAuctionRecord.id}`
        pendingAuctionCompomentParams.photosArray = pendingAuctionRecord.photos
        pendingAuctionCompomentParams.startDate = new Date(pendingAuctionRecord.date)
        pendingAuctionCompomentParams.type = pendingAuctionRecord.type
        pendingAuctionCompomentParams.ruleset = pendingAuctionRecord.ruleset
        pendingAuctionCompomentParams.author =
            `${pendingAuctionRecord.author.first_name} ${pendingAuctionRecord.author.last_name}`
            ?? "Non existing user"

        return new PendingAuctionComponent(pendingAuctionCompomentParams);
    }


    #computeImgTagsHtml(photosArray)
    {
        let result = ""

        if (photosArray === undefined) return result

        result += `
            <img src="${getImageAssetsPath()}/${photosArray[0].path}" alt="Auction Photo" width="200">
        `
        return result
    }
}