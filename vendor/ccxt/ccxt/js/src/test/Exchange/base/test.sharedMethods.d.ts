declare function logTemplate(exchange: any, method: any, entry: any): string;
declare function isTemporaryFailure(e: any): boolean;
declare function assertType(exchange: any, skippedProperties: any, entry: any, key: any, format: any): boolean;
declare function assertStructure(exchange: any, skippedProperties: any, method: any, entry: any, format: any, emptyAllowedFor?: any[]): void;
declare function assertTimestamp(exchange: any, skippedProperties: any, method: any, entry: any, nowToCheck?: any, keyNameOrIndex?: any): void;
declare function assertTimestampAndDatetime(exchange: any, skippedProperties: any, method: any, entry: any, nowToCheck?: any, keyNameOrIndex?: any): void;
declare function assertCurrencyCode(exchange: any, skippedProperties: any, method: any, entry: any, actualCode: any, expectedCode?: any): void;
declare function assertValidCurrencyIdAndCode(exchange: any, skippedProperties: any, method: any, entry: any, currencyId: any, currencyCode: any): void;
declare function assertSymbol(exchange: any, skippedProperties: any, method: any, entry: any, key: any, expectedSymbol?: any): void;
declare function assertGreater(exchange: any, skippedProperties: any, method: any, entry: any, key: any, compareTo: any): void;
declare function assertGreaterOrEqual(exchange: any, skippedProperties: any, method: any, entry: any, key: any, compareTo: any): void;
declare function assertLess(exchange: any, skippedProperties: any, method: any, entry: any, key: any, compareTo: any): void;
declare function assertLessOrEqual(exchange: any, skippedProperties: any, method: any, entry: any, key: any, compareTo: any): void;
declare function assertEqual(exchange: any, skippedProperties: any, method: any, entry: any, key: any, compareTo: any): void;
declare function assertNonEqual(exchange: any, skippedProperties: any, method: any, entry: any, key: any, compareTo: any): void;
declare function assertInArray(exchange: any, skippedProperties: any, method: any, entry: any, key: any, expectedArray: any): void;
declare function assertFeeStructure(exchange: any, skippedProperties: any, method: any, entry: any, key: any): void;
declare function assertTimestampOrder(exchange: any, method: any, codeOrSymbol: any, items: any, ascending?: boolean): void;
declare function assertInteger(exchange: any, skippedProperties: any, method: any, entry: any, key: any): void;
declare function checkPrecisionAccuracy(exchange: any, skippedProperties: any, method: any, entry: any, key: any): void;
declare function removeProxyOptions(exchange: any, skippedProperties: any): any[];
declare function setProxyOptions(exchange: any, skippedProperties: any, proxyUrl: any, httpProxy: any, httpsProxy: any, socksProxy: any): void;
declare const _default: {
    logTemplate: typeof logTemplate;
    isTemporaryFailure: typeof isTemporaryFailure;
    assertTimestamp: typeof assertTimestamp;
    assertTimestampAndDatetime: typeof assertTimestampAndDatetime;
    assertStructure: typeof assertStructure;
    assertSymbol: typeof assertSymbol;
    assertCurrencyCode: typeof assertCurrencyCode;
    assertInArray: typeof assertInArray;
    assertFeeStructure: typeof assertFeeStructure;
    assertTimestampOrder: typeof assertTimestampOrder;
    assertGreater: typeof assertGreater;
    assertGreaterOrEqual: typeof assertGreaterOrEqual;
    assertLess: typeof assertLess;
    assertLessOrEqual: typeof assertLessOrEqual;
    assertEqual: typeof assertEqual;
    assertNonEqual: typeof assertNonEqual;
    assertInteger: typeof assertInteger;
    checkPrecisionAccuracy: typeof checkPrecisionAccuracy;
    assertValidCurrencyIdAndCode: typeof assertValidCurrencyIdAndCode;
    assertType: typeof assertType;
    removeProxyOptions: typeof removeProxyOptions;
    setProxyOptions: typeof setProxyOptions;
};
export default _default;