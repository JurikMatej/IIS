import { createComponent, getImageAssetsPath } from "../componentUtils";
import { getSecondsFromTimeString, getTimeComponentsFromTimeString, getMilisecondsFromTimeString,
    formatDateTime, dateAddTime }
    from "../../utils/dateTimeUtils"


/**
 * @brief AuctionComponent construction params
 * @type {{timeLimit: string, winner: string, author: string, name: string, ruleset: string, id: string, photosArray: string, detailLink: string, type: string, startDate: string, desc: string}}
 */
export const AuctionComponentParams = {
    id: "",
    name: "",
    detailLink: "",
    photosArray: "",
    desc: "",
    startDate: "",
    timeLimit: "",
    winner: "",
    type: "",
    ruleset: "",
    author: ""
}

/**
 * @brief Component representing an auction
 */
export class AuctionComponent
{
    constructor(params) // AuctionComponentParams
    {
        // Set instance fields
        this.params = params
        this.auctionStartDate = params.startDate
        this.auctionStarted = this.#determineAuctionStart(this.auctionStartDate)

        // Compute needed properties
        const startFormat = this.#computeStartFormat() // Additional date description

        const computedIMGs = this.#computeImgTagsHtml(params.photosArray)

        const computedStatus = this.#computeStatus(
            params.timeLimit,
            !!params.winner,
            !!params.approver
        )

        // Create the component
        const componentHtml = `
            <div class="auction-component" data-id="${params.id}">                        
                <h3>
                    <a class="auction-detail-link" href="${params.detailLink}">${params.name}</a>
                </h3>
                
                ${computedIMGs}
                
                <p class="auction-description">Description: ${params.desc}</p>
                <h2 class="auction-date">${startFormat}: ${formatDateTime(params.startDate)}</h2>
                <h2 class="auction-status" style="color:${computedStatus.color}">${computedStatus.text}</h2>
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
     * @brief Create a new AuctionComponent from an AuctionRecord
     * @param auctionRecord
     * @returns {AuctionComponent}
     */
    static fromDbAuctionRecord(auctionRecord)
    {
        const auctionCompomentParams = Object.assign(AuctionComponentParams);

        // Setup AuctionComponentParams params
        auctionCompomentParams.id = auctionRecord.id
        auctionCompomentParams.name = auctionRecord.name
        auctionCompomentParams.detailLink = `/auctions/${auctionRecord.id}`
        auctionCompomentParams.photosArray = auctionRecord.photos
        auctionCompomentParams.desc= auctionRecord.description
        auctionCompomentParams.startDate = new Date(auctionRecord.date)
        auctionCompomentParams.timeLimit = auctionRecord.time_limit
        auctionCompomentParams.winner = auctionRecord.winner
        auctionCompomentParams.type = auctionRecord.type
        auctionCompomentParams.ruleset = auctionRecord.ruleset
        auctionCompomentParams.author =
            `${auctionRecord.author.first_name} ${auctionRecord.author.last_name}`

        return new AuctionComponent(auctionCompomentParams);
    }


    /**
     * @brief Determine whether the auction has already started
     * @param start_date
     * @returns {boolean}
     */
    #determineAuctionStart(start_date)
    {
        const today = new Date()
        if (today >= start_date)
            return true
        return false
    }


    /**
     * @brief Get the proper format for start date description
     * @returns {string}
     */
    #computeStartFormat()
    {
        if (this.auctionStarted)
            return "Starting on"
        return "Started on"
    }


    /**
     * @brief Get html for thumbnail (first) photo
     * @param photosArray
     * @returns {string}
     *
     * @todo reuse {for of photosArray} in AuctionDetailComponent
     */
    #computeImgTagsHtml(photosArray)
    {
        let result = ""

        if (photosArray === undefined) return result


        // for (const photo of photosArray)
        //     result += `
        //         <img src="${getImageAssetsPath()}/${photo.path}" alt="Auction Photo" width="200">
        //     `

        result += `
            <img src="${getImageAssetsPath()}/${photosArray[0].path}" alt="Auction Photo" width="200">
        `
        return result
    }


    /**
     * @brief Compute the color and text of auction status paragraph
     * @param {object} fields - color, text (string)
     */
    #computeStatus(_timeLimit, auctionHasWinner, auctionHasApprover)
    {
        // Auction runs until ended by an authority
        if (!_timeLimit)
        {
            if (!this.auctionStarted)
            {
                return {color: "blue", text: "Not started yet"}
            }
            else if (auctionHasWinner)
            {
                return {color: "green", text: "Running"}
            }
            else
            {
                return {color: "red", text: "Finished"}
            }
        }
        // Auction has a time limit
        else
        {
            // Needed constants
            const auctionEndDate = this.#calculateAuctionEndDate(_timeLimit)
            const formattedAuctionEndDate = formatDateTime(auctionEndDate)
            const today = new Date()

            if (auctionEndDate > today && this.auctionStarted)
            {
                return {color: "green", text: `Running until: ${formattedAuctionEndDate}`}
            }
            else if (auctionEndDate > today && this.auctionStarted)
            {
                return {color: "blue", text: `Running Until: ${formattedAuctionEndDate}`}
            }
            else
            {
                return {color: "red", text: `Finished on: ${formattedAuctionEndDate}`}
            }
        }

    }


    /**
     * @brief Get auction end date
     * @param timelimit
     * @returns {Date}
     */
    #calculateAuctionEndDate(timelimit)
    {
        return dateAddTime(this.auctionStartDate, timelimit)
    }
}

