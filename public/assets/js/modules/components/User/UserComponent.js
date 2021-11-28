import { createComponent, getImageAssetsPath } from "../componentUtils";


/**
 * @brief UserComponent construction params
 * @type {{role: string, name: string, id: string, detailLink: string}}
 */
export const UserComponentParams = {
    id: "",
    name: "",
    detailLink: "",
    role: ""
}

/**
 * @brief Component representing an user
 */
export class UserComponent {
    static elementClass = ".user-component"

    constructor(params) // UserComponentParams
    {
        // Set instance fields
        this.elementClass = ".user-component"
        this.params = params

        // Create the component
        const componentHtml = `
            <div class="user-component" data-id="${params.id}">                        
                <h3>
                    <a class="user-detail-link" href="${params.detailLink}">${params.name}</a>
                </h3>

                <p class="user-role">${params.role}</p>
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
     * @brief Create a new UserComponent from an UserRecord
     * @param userRecord
     * @returns {UserComponent}
     */
    static fromDbRecord(userRecord)
    {
        const userComponentParams = Object.assign(UserComponentParams);

        // Setup AuctionComponentParams params
        userComponentParams.id = userRecord.id
        userComponentParams.name = `${userRecord.first_name} ${userRecord.last_name}`
        userComponentParams.detailLink = `/users/${userRecord.id}`
        userComponentParams.role = userRecord.role

        return new UserComponent(userComponentParams);
    }
}