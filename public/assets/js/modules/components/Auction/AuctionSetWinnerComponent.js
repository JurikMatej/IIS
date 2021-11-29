import { createComponent, getImageAssetsPath } from "../componentUtils";
import { getSecondsFromTimeString, getTimeComponentsFromTimeString, getMilisecondsFromTimeString,
    formatDateTime, dateAddTime }
    from "../../utils/dateTimeUtils"


/**
 * @brief AuctionSetWinnerComponent construction params
 * @type {{approveLink: string, rejectLink: string, name: string, id: string}}
 */
export const AuctionSetWinnerComponentParams = {

    winningName: "",
    winningValue: "", // must be greater than 0
    winningBidType: "",
    winnerSetLink: "",
    auctionStarted: "",
    auctionTimelimit: "",
    auctionHasWinner: "",
    winnerName: "",
    auctionFinished: "", // has no limit or has passed limit

}

/**
 * @brief Component representing an set winner component for specific auction
 */
export class AuctionSetWinnerComponent
{
    static elementClass = ".auction-winner-set-component"

    constructor(params) // AuctionSetWinnerComponentParams
    {
        // Set instance fields
        this.elementClass = ".auction-winner-set-component"
        this.params = params


        // Create the component
        const componentHtml = `  
            <div class="auction-winner-set-component">
                <h2> Define winner </h2>
                
                <div id="bid-winning-user-set">
                    <p id="winning-bid-type">${params.winningBidType} bid: </p>
                    
                    <div id="bid-winner" hidden>
                        <h3>
                            <span class="bid-winner-value">${params.winningValue}</span> $
                            by 
                            <span class="bid-winner-name">${params.winningName}</span>                            
                        </h3>
                        
                        <a href="${params.winnerSetLink}" class="btn btn-success">
                            Set winner
                        </a>
                    </div>        
                </div>
                

                <p id="bid-winner-set-wait" hidden>You have to wait until the auction starts, to define a winner!</p>

                
                <h2 id="winner-already-set" hidden> 
                    Winner is ${params.winnerName}
                    with bid ${params.winningValue} $
                </h2>
               
            </div>`

        this.html = createComponent(componentHtml);
    }


    /**
     * @brief Get instance html property
     * @returns {HTMLElement}
     */
    render()
    {
        return this.#setUpComponentHTML()
    }


    /**
     * @brief Modify component's html according to its state (from params)
     * @return {*}
     *
     * @todo Not-Optimal and ugly - rethink & rewrite
     *       (NO TIME, but Component hierarchy (conditional sub component rendering) would solve this)
     */
    #setUpComponentHTML()
    {

        // const component = $(this.html)
        const component = $(this.html)
        const componentChildren = Object.values(component.children())

        // Component partial elements definition
        const winningBidType   = component.children().find("#winning-bid-type")
        const bidWinner        = component.children().find("#bid-winner")
        const bidWinnerSetWait = $(componentChildren[2]) // Hack - wouldnt work like the upper two
        const winnerAlreadySet = $(componentChildren[3]) // Hack - same

        // Selective component partials rendering
        if (!this.params.auctionHasWinner)
        {
            if (this.params.auctionStarted)
            {
                if (this.params.auctionTimelimit === null || this.params.auctionFinished)
                {
                    // OK
                    winningBidType.removeAttr("hidden")
                    bidWinnerSetWait.remove()
                    winnerAlreadySet.remove()

                    if (this.params.winningValue !== null && this.params.winningValue !== 0)
                    {
                        bidWinner.removeAttr("hidden")
                    }
                    else
                    {
                        bidWinner.remove()
                    }
                }
                else
                {
                    // OK
                    bidWinnerSetWait.removeAttr("hidden")
                    winningBidType.remove()
                    bidWinner.remove()
                    winnerAlreadySet.remove()
                }
            }
            else
            {
                // OK
                bidWinnerSetWait.removeAttr("hidden")
                winningBidType.remove()
                bidWinner.remove()
                winnerAlreadySet.remove()
            }
        }
        else
        {
            // ?
            winnerAlreadySet.removeAttr("hidden")
            bidWinnerSetWait.remove()
            winningBidType.remove()
            bidWinner.remove()
        }


        return component
    }


    /**
     * @brief Create a new AuctionSetWinnerComponent from an AuctionWinnerRecord
     * @param auctionWinnerRecord
     * @returns {AuctionSetWinnerComponent}
     *
     * @todo Refactor :(
     */
    static fromDbRecord(auctionWinnerRecord)
    {
        // Setup AuctionWinnerSetComponentParams params
        const auctionSetWinnerComponentParams = Object.assign(AuctionSetWinnerComponentParams);


        // Build the whole name of the user holding the winning bet
        auctionSetWinnerComponentParams.winningName =
            `${auctionWinnerRecord.user.first_name} ${auctionWinnerRecord.user.last_name}`

        // Get the (currently) winning bet value
        auctionSetWinnerComponentParams.winningValue = auctionWinnerRecord.value


        // Determine whether the winning bet is to be represented as highest or lowest by auction type
        if (auctionWinnerRecord.auction.type === "ascending-bid")
            auctionSetWinnerComponentParams.winningBidType = "Highest"
        else
            auctionSetWinnerComponentParams.winningBidType = "Lowest"


        // Build url for setting the winning user as concluding winner
        auctionSetWinnerComponentParams.winnerSetLink =
            `/auctions/${auctionWinnerRecord.auction.id}/winner/${auctionWinnerRecord.user.id}`


        // Determine whether the related auction has started
        const auctionStartDate = new Date(auctionWinnerRecord.auction.date)
        auctionSetWinnerComponentParams.auctionStarted =
            AuctionSetWinnerComponent.#computeAuctionStarted(
                auctionStartDate
            )


        // Get related auction time limit
        auctionSetWinnerComponentParams.auctionTimelimit = auctionWinnerRecord.auction.time_limit


        // Determine related auction has a winner
        const auctionHasWinner = (auctionWinnerRecord.auction.winner_id !== 0)
        auctionSetWinnerComponentParams.auctionHasWinner = auctionHasWinner


        // Get winner's name
        if (auctionHasWinner)
            auctionSetWinnerComponentParams.winnerName =
                `${auctionWinnerRecord.auction.winner.first_name} ${auctionWinnerRecord.auction.winner.last_name}`


        // Determine whether the related auction has finished
        if (auctionHasWinner)
        {
            auctionSetWinnerComponentParams.auctionFinished = true
        }
        else if (auctionWinnerRecord.auction.time_limit !== null)
        {
            auctionSetWinnerComponentParams.auctionFinished =
                AuctionSetWinnerComponent.#computeAuctionFinished(
                    auctionStartDate,
                    auctionWinnerRecord.auction.time_limit
                )
        }
        else
        {
            auctionSetWinnerComponentParams.auctionFinished = false
        }


        return new AuctionSetWinnerComponent(auctionSetWinnerComponentParams);
    }


    /**
     * @brief Determine whether the auction has already started
     * @param startDate
     * @return {boolean}
     */
    static #computeAuctionStarted(startDate)
    {
        const today = new Date()
        if (today >= startDate)
            return true
        return false
    }


    /**
     * @brief Determine whether the auction has already concluded
     * @param startDate
     * @param timelimit
     * @return {boolean}
     */
    static #computeAuctionFinished(startDate, timelimit)
    {
        const today = new Date()
        const finishDate = dateAddTime(startDate, timelimit)
        if (today >= finishDate)
            return true
        return false
    }
}

