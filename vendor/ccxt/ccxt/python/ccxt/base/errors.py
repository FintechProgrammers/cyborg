error_hierarchy = {
    'BaseError': {
        'ExchangeError': {
            'AuthenticationError': {
                'PermissionDenied': {
                    'AccountNotEnabled': {},
                },
                'AccountSuspended': {},
            },
            'ArgumentsRequired': {},
            'BadRequest': {
                'BadSymbol': {},
                'MarginModeAlreadySet': {},
                'NoChange': {},
                'OperationRejected': {},
            },
            'BadResponse': {
                'NullResponse': {},
            },
            'InsufficientFunds': {},
            'InvalidAddress': {
                'AddressPending': {},
            },
            'InvalidOrder': {
                'OrderNotFound': {},
                'OrderNotCached': {},
                'CancelPending': {},
                'OrderImmediatelyFillable': {},
                'OrderNotFillable': {},
                'DuplicateOrderId': {},
                'ContractUnavailable': {},
            },
            'NotSupported': {},
            'ProxyError': {},
        },
        'OperationFailed': {
            'NetworkError': {
                'DDoSProtection': {
                    'RateLimitExceeded': {},
                },
                'ExchangeNotAvailable': {
                    'OnMaintenance': {},
                },
                'InvalidNonce': {},
                'RequestTimeout': {},
            },
        },
        'ExchangeClosedByUser': {},
    },
}


class BaseError(Exception):
    pass


class ExchangeError(BaseError):
    pass


class AuthenticationError(ExchangeError):
    pass


class PermissionDenied(AuthenticationError):
    pass


class AccountNotEnabled(PermissionDenied):
    pass


class AccountSuspended(AuthenticationError):
    pass


class ArgumentsRequired(ExchangeError):
    pass


class BadRequest(ExchangeError):
    pass


class BadSymbol(BadRequest):
    pass


class MarginModeAlreadySet(BadRequest):
    pass


class NoChange(BadRequest):
    pass


class OperationRejected(BadRequest):
    pass


class BadResponse(ExchangeError):
    pass


class NullResponse(BadResponse):
    pass


class InsufficientFunds(ExchangeError):
    pass


class InvalidAddress(ExchangeError):
    pass


class AddressPending(InvalidAddress):
    pass


class InvalidOrder(ExchangeError):
    pass


class OrderNotFound(InvalidOrder):
    pass


class OrderNotCached(InvalidOrder):
    pass


class CancelPending(InvalidOrder):
    pass


class OrderImmediatelyFillable(InvalidOrder):
    pass


class OrderNotFillable(InvalidOrder):
    pass


class DuplicateOrderId(InvalidOrder):
    pass


class ContractUnavailable(InvalidOrder):
    pass


class NotSupported(ExchangeError):
    pass


class ProxyError(ExchangeError):
    pass


class OperationFailed(BaseError):
    pass


class NetworkError(OperationFailed):
    pass


class DDoSProtection(NetworkError):
    pass


class RateLimitExceeded(DDoSProtection):
    pass


class ExchangeNotAvailable(NetworkError):
    pass


class OnMaintenance(ExchangeNotAvailable):
    pass


class InvalidNonce(NetworkError):
    pass


class RequestTimeout(NetworkError):
    pass


class ExchangeClosedByUser(BaseError):
    pass


__all__ = [
    'error_hierarchy',
    'BaseError',
    'ExchangeError',
    'AuthenticationError',
    'PermissionDenied',
    'AccountNotEnabled',
    'AccountSuspended',
    'ArgumentsRequired',
    'BadRequest',
    'BadSymbol',
    'MarginModeAlreadySet',
    'NoChange',
    'OperationRejected',
    'BadResponse',
    'NullResponse',
    'InsufficientFunds',
    'InvalidAddress',
    'AddressPending',
    'InvalidOrder',
    'OrderNotFound',
    'OrderNotCached',
    'CancelPending',
    'OrderImmediatelyFillable',
    'OrderNotFillable',
    'DuplicateOrderId',
    'ContractUnavailable',
    'NotSupported',
    'ProxyError',
    'OperationFailed',
    'NetworkError',
    'DDoSProtection',
    'RateLimitExceeded',
    'ExchangeNotAvailable',
    'OnMaintenance',
    'InvalidNonce',
    'RequestTimeout',
    'ExchangeClosedByUser'
]