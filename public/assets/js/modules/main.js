import { AuctionComponent } from "./components/Auction/AuctionComponent"
import { registerAuctionComponentsRefresh } from "./ajax/refreshAuctions"
import { registerPendingAuctionComponentsRefresh } from "./ajax/refreshPendingAuctions"
import { registerUserComponentsRefresh } from "./ajax/refreshUsers"

/** Register all interval based functionality */
registerAuctionComponentsRefresh()
registerPendingAuctionComponentsRefresh()
registerUserComponentsRefresh()

/**
 * Components:
 *
 *      DONE
 *      AuctionComponent: AuctionsWrapper
 *           StartTime, EndTime, Running?, RunningUntil?
 *
 *      NEXT
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
 *
 *      AuctionWaitingUsersComponent: AuctionOptionsWrapper
 *          list [user with approve or reject option]
 *
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