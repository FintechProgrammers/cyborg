// -------------------------------------------------------------------------------

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

// -------------------------------------------------------------------------------

import { implicitReturnType } from '../base/types.js';
import { Exchange as _Exchange } from '../base/Exchange.js';

interface Exchange {
    publicGetCurrencyProfile (params?: {}): Promise<implicitReturnType>;
    publicGetCurrencyLimits (params?: {}): Promise<implicitReturnType>;
    publicGetLastPricePair (params?: {}): Promise<implicitReturnType>;
    publicGetLastPricesCurrencies (params?: {}): Promise<implicitReturnType>;
    publicGetOhlcvHdYyyymmddPair (params?: {}): Promise<implicitReturnType>;
    publicGetOrderBookPair (params?: {}): Promise<implicitReturnType>;
    publicGetTickerPair (params?: {}): Promise<implicitReturnType>;
    publicGetTickersCurrencies (params?: {}): Promise<implicitReturnType>;
    publicGetTradeHistoryPair (params?: {}): Promise<implicitReturnType>;
    publicPostConvertPair (params?: {}): Promise<implicitReturnType>;
    publicPostPriceStatsPair (params?: {}): Promise<implicitReturnType>;
    privatePostActiveOrdersStatus (params?: {}): Promise<implicitReturnType>;
    privatePostArchivedOrdersPair (params?: {}): Promise<implicitReturnType>;
    privatePostBalance (params?: {}): Promise<implicitReturnType>;
    privatePostCancelOrder (params?: {}): Promise<implicitReturnType>;
    privatePostCancelOrdersPair (params?: {}): Promise<implicitReturnType>;
    privatePostCancelReplaceOrderPair (params?: {}): Promise<implicitReturnType>;
    privatePostClosePositionPair (params?: {}): Promise<implicitReturnType>;
    privatePostGetAddress (params?: {}): Promise<implicitReturnType>;
    privatePostGetCryptoAddress (params?: {}): Promise<implicitReturnType>;
    privatePostGetMyfee (params?: {}): Promise<implicitReturnType>;
    privatePostGetOrder (params?: {}): Promise<implicitReturnType>;
    privatePostGetOrderTx (params?: {}): Promise<implicitReturnType>;
    privatePostOpenOrdersPair (params?: {}): Promise<implicitReturnType>;
    privatePostOpenOrders (params?: {}): Promise<implicitReturnType>;
    privatePostOpenPositionPair (params?: {}): Promise<implicitReturnType>;
    privatePostOpenPositionsPair (params?: {}): Promise<implicitReturnType>;
    privatePostPlaceOrderPair (params?: {}): Promise<implicitReturnType>;
    privatePostRawTxHistory (params?: {}): Promise<implicitReturnType>;
}
abstract class Exchange extends _Exchange {}

export default Exchange