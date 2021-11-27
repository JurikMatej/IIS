// Polyfill functionality - async, await
import "core-js/stable";
import "regenerator-runtime/runtime";

import axios from "axios"
import { $exists } from "../utils/jQueryElementUtils"
import { AJAX_RELOAD_INTERVAL } from "./ajaxConfig"
import { UserComponent, UserComponentParams } from "../components/User/UserComponent"


/** @const Related ajax endpoint */
const USERS_ENDPOINT = "http://localhost:8080/ajax/users"

/** @const View's approved users wrapper */
const $USERS_WRAPPER = $(".users-wrapper")


/**
 * @brief Register an function that runs UserComponents' refresh every ${AJAX_RELOAD_INTERVAL}ms
 * @return {void}
 */
export function registerUserComponentsRefresh()
{
    if ($exists($USERS_WRAPPER))
    {
        setInterval(refreshUserComponents, AJAX_RELOAD_INTERVAL)
    }
}


/**
 * @brief Fetch all users available at ajax endpoint
 * @returns {Promise<*>}
 */
async function fetchUsers()
{
    const users = await axios(USERS_ENDPOINT)

    return users.data.data
}


/**
 * @brief Filter out users already present in the user-wrapper
 * @param users
 * @return {*}
 */
function filterExistingUsers(users) {
    // Get existing users' collection
    const existingUsers = Array.from($(".user-component"))

    // Extract their IDs
    const existingUsersIDs = existingUsers.map(user => parseInt(user.dataset.id))

    // Filter out the already existing users by ID
    const newUsers = users.filter(
        user => !existingUsersIDs.includes(user.id)
    )

    return newUsers
}


/**
 * @brief Wrapper for instantiating new UserComponents from an array
 * @param newUsers
 * @returns {*}
 */
function prepareUserComponents(newUsers) {
    return newUsers.reduce(
        (accumulatedUsers, newUser) => { // Build new array from
            return [
                ...accumulatedUsers,
                // Instantiate new AuctionComponent
                UserComponent.fromDbUserRecord(newUser)
            ]
        }, []
    );
}


/**
 * @brief Prepend all new users to the top of the user wrapper
 * @param userComponents
 */
function addUserComponentsIntoView(userComponents)
{
    for (const userComponent of userComponents)
    {
        // Final integrity/duplicity check
        const duplicitUserComponent = $(`.user-component[data-id=${userComponent.params.id}]`)
        if (!$exists(duplicitUserComponent))
        {
            // Add into view
            $USERS_WRAPPER.prepend(
                userComponent.render()
            )
        }
    }
}


/**
 * @brief Refresh users present on current view (add new, remove deleted)
 * @returns {Promise<void>}
 */
async function refreshUserComponents()
{
    // Fetch all users
    const users = await fetchUsers()
    const newUsers = filterExistingUsers(users);

    // TODO EXTRACT TO ADD_NEW

    // Instantiate UserComponents from all the new users
    const newUserComponents = prepareUserComponents(
        Object.values(newUsers)
    )

    // Finally add new user componets to view
    addUserComponentsIntoView(newUserComponents)



    // TODO CODE REMOVE_DELETED
}