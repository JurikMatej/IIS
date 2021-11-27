import { AuctionComponent } from "./components/Auction/AuctionComponent"
import { registerAuctionComponentsRefresh } from "./ajax/refreshAuctions"
import { registerPendingAuctionComponentsRefresh } from "./ajax/refreshPendingAuctions"

/** Register all interval based functionality */
registerAuctionComponentsRefresh();
registerPendingAuctionComponentsRefresh();
//...

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
 *      NEXT
 *      PendingAuctionComponent: PendingAuctionsWrapper
 *          whole
 *
 * -----------------------------------------------------
 *
 *      AuctionWaitingUsersComponet: AuctionOptionsWrapper
 *          list [user with approve or reject option]
 *
 *      AuctionWinnerSetComponnent: AuctionOptionsWrapper
 *          user with "set" link
 *
 * -----------------------------------------------------
 *
 *      UserComponent: UsersWrapper
 *          link to UserDetail, Role
 *
 */