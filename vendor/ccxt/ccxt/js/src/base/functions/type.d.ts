import { implicitReturnType, Int, Str, IndexType } from '../types.js';
declare const isNumber: (number: unknown) => boolean;
declare const isInteger: (number: unknown) => boolean;
declare const isArray: (arg: any) => arg is any[];
declare const hasProps: (o: any) => boolean;
declare const isString: (s: any) => boolean;
declare const isObject: (o: any) => boolean;
declare const isDictionary: (o: any) => boolean;
declare const isStringCoercible: (x: any) => any;
declare const prop: (o: any, k: any) => any;
declare const asFloat: (x: any) => number;
declare const asInteger: (x: any) => number;
declare const safeFloat: (o: implicitReturnType, k: IndexType, $default?: number) => number;
declare const safeInteger: (o: implicitReturnType, k: IndexType, $default?: number) => Int;
declare const safeIntegerProduct: (o: implicitReturnType, k: IndexType, $factor: number, $default?: number) => Int;
declare const safeTimestamp: (o: implicitReturnType, k: IndexType, $default?: number) => number;
declare const safeValue: (o: implicitReturnType, k: IndexType, $default?: any) => any;
declare const safeString: (o: implicitReturnType, k: IndexType, $default?: string) => Str;
declare const safeStringLower: (o: implicitReturnType, k: IndexType, $default?: string) => Str;
declare const safeStringUpper: (o: implicitReturnType, k: IndexType, $default?: string) => Str;
declare const safeFloat2: (o: implicitReturnType, k1: IndexType, k2: IndexType, $default?: number) => number;
declare const safeInteger2: (o: implicitReturnType, k1: IndexType, k2: IndexType, $default?: number) => Int;
declare const safeIntegerProduct2: (o: implicitReturnType, k1: IndexType, k2: IndexType, $factor: number, $default?: number) => Int;
declare const safeTimestamp2: (o: implicitReturnType, k1: IndexType, k2: IndexType, $default?: any) => Int;
declare const safeValue2: (o: implicitReturnType, k1: IndexType, k2: IndexType, $default?: any) => any;
declare const safeString2: (o: implicitReturnType, k1: IndexType, k2: IndexType, $default?: string) => Str;
declare const safeStringLower2: (o: implicitReturnType, k1: IndexType, k2: IndexType, $default?: string) => Str;
declare const safeStringUpper2: (o: implicitReturnType, k1: IndexType, k2: IndexType, $default?: string) => Str;
declare const safeFloatN: (o: implicitReturnType, k: (IndexType)[], $default?: number) => number;
declare const safeIntegerN: (o: implicitReturnType, k: (IndexType)[], $default?: number) => Int;
declare const safeIntegerProductN: (o: implicitReturnType, k: (IndexType)[], $factor: number, $default?: number) => Int;
declare const safeTimestampN: (o: implicitReturnType, k: (IndexType)[], $default?: number) => Int;
declare const safeValueN: (o: implicitReturnType, k: (IndexType)[], $default?: any) => any;
declare const safeStringN: (o: implicitReturnType, k: (IndexType)[], $default?: string) => Str;
declare const safeStringLowerN: (o: implicitReturnType, k: (IndexType)[], $default?: string) => Str;
declare const safeStringUpperN: (o: implicitReturnType, k: (IndexType)[], $default?: string) => Str;
export { isNumber, isInteger, isArray, isObject, isString, isStringCoercible, isDictionary, hasProps, prop, asFloat, asInteger, safeFloat, safeInteger, safeIntegerProduct, safeTimestamp, safeValue, safeString, safeStringLower, safeStringUpper, safeFloat2, safeInteger2, safeIntegerProduct2, safeTimestamp2, safeValue2, safeString2, safeStringLower2, safeStringUpper2, safeFloatN, safeIntegerN, safeIntegerProductN, safeTimestampN, safeValueN, safeStringN, safeStringLowerN, safeStringUpperN, };