import { UserComponent } from "../../../components/User/UserComponent"
import { registerStandardComponentsRefresh }
    from "../refreshUtils"

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
    registerStandardComponentsRefresh(
        $USERS_WRAPPER,
        [UserComponent, $USERS_WRAPPER, USERS_ENDPOINT]
    );
}
