import { AuctionComponent } from "./components/Auction/AuctionComponent"
import { registerAuctionComponentsRefresh } from "./ajax/component-refreshers/Auction/refreshAuctions"
import { registerPendingAuctionComponentsRefresh } from "./ajax/component-refreshers/Auction/refreshPendingAuctions"
import { registerUserComponentsRefresh } from "./ajax/component-refreshers/User/refreshUsers"
import { registerAuctionPendingUserComponentsRefresh } from "./ajax/component-refreshers/Auction/refreshAuctionPendingUsers"

/** Register all interval based functionality */
registerAuctionComponentsRefresh()
registerPendingAuctionComponentsRefresh()
registerUserComponentsRefresh()
registerAuctionPendingUserComponentsRefresh()


/**
 * Components:
 *
 *      DONE
 *      AuctionComponent: AuctionsWrapper
 *           StartTime, EndTime, Running?, RunningUntil?
 *
 *      LAST
 *      AuctionDetailComponent: AuctionDetailWrapper
 *           StartTime, EndTime, Winner
 *
 *                  AuctionUserOptionsComponent: AuctionDetailWrapper > UserOptionsWrapper
 *                      registerState, waitingState, bidState, closedState
 *
 *                  AuctionLicitatorOptionsComponent: AuctionDetailWrapper > LicitatorOptionsWrapper
 *                      manageUsersState, closedState
 *
 *                  AuctionAdminOptionsComponent: AuctionDetailWrapper > AdminOptionsWrapper
 *                      runningState, bidState, closedState
 *
 *      DONE
 *      PendingAuctionComponent: PendingAuctionsWrapper
 *          whole
 *
 * -----------------------------------------------------
 *      DONE
 *      AuctionPendingUsersComponent: AuctionOptionsWrapper
 *          list [user with approve or reject option]
 *
 *      NEXT
 *      AuctionWinnerSetComponent: AuctionOptionsWrapper
 *          user with "set" link
 *
 * -----------------------------------------------------
 *
 *      DONE
 *      UserComponent: UsersWrapper
 *          link to UserDetail, Role
 *
 */