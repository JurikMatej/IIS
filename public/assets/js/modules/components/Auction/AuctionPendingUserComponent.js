import { createComponent, getImageAssetsPath } from "../componentUtils";
import { getSecondsFromTimeString, getTimeComponentsFromTimeString, getMilisecondsFromTimeString,
    formatDateTime, dateAddTime }
    from "../../utils/dateTimeUtils"


/**
 * @brief AuctionPendingUserComponent construction params
 * @type {{approveLink: string, rejectLink: string, name: string, id: string}}
 */
export const AuctionPendingUserComponentParams = {
    id: "",
    name: "",
    approveLink: "",
    rejectLink: "",
}

/**
 * @brief Component representing an user waiting to be approved for a specific auction
 */
export class AuctionPendingUserComponent
{
    static elementClass = ".auction-pending-user-component"

    constructor(params) // AuctionPendingUserComponentParams
    {
        // Set instance fields
        this.elementClass = ".auction-pending-user-component"
        this.params = params

        // Create the component
        const componentHtml = `
            <div class="auction-pending-user-component" data-id="${params.id}">
                <h3 style="color:purple;">${params.name}</h3>
                <a href="${params.approveLink}" class="btn btn-success" 
                   onclick="return confirm('Do you want to approve this user on this auction ?')">
                    Approve
                </a>
                <a href="${params.rejectLink}" class="btn btn-danger"
                   onclick="return confirm('Do you want to reject this user from this auction ?')">
                    Reject
                </a>

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
     * @brief Create a new AuctionPendingUserComponent from an AuctionPendingUserRecord
     * @param auctionPendingUserRecord
     * @returns {AuctionPendingUserComponent}
     */
    static fromDbRecord(auctionPendingUserRecord)
    {
        const auctionPendingUserCompomentParams = Object.assign(AuctionPendingUserComponentParams);

        // Setup AuctionPendingUserComponentParams params
        auctionPendingUserCompomentParams.id = auctionPendingUserRecord.id
        auctionPendingUserCompomentParams.name =
            `${auctionPendingUserRecord.first_name} ${auctionPendingUserRecord.last_name}`
        auctionPendingUserCompomentParams.approveLink =
            `/auctions/${auctionPendingUserRecord.related_auction}/users/${auctionPendingUserRecord.related_bid}/approve`

        auctionPendingUserCompomentParams.rejectLink =
            `/auctions/${auctionPendingUserRecord.related_auction}/users/${auctionPendingUserRecord.related_bid}/reject`

        return new AuctionPendingUserComponent(auctionPendingUserCompomentParams);
    }
}

