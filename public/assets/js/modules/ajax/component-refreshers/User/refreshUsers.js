import { UserComponent } from "../../../components/User/UserComponent"
import { registerStandardComponentsRefresh }
    from "../refreshUtils"

/** @const Current browser location url */
const LOCATION = document.location.href

/** @const Computed host url */
const HOST = LOCATION.split('/')[2]

/** @const Related ajax endpoint */
const USERS_ENDPOINT = `/ajax/users`

/** @const View's approved users wrapper */
const $USERS_WRAPPER = $(".users-wrapper")


/**
 * @brief Register an function that runs UserComponents' refresh every ${AJAX_RELOAD_INTERVAL}ms
 * @return {void}
 */
export function registerUserComponentsRefresh()
{
    registerStandardComponentsRefresh(
        $USERS_WRAPPER,
        [UserComponent, $USERS_WRAPPER, USERS_ENDPOINT]
    );
}
