# Parcel DE Shipping API (Post & Parcel Germany)

## API Information

- **Title:** Parcel DE Shipping API (Post & Parcel Germany)
- **Version:** 2.1.14
- **Source Spec SHA-256:** 58db0a49f0160b83e97758dfc88f21d9251f58d0bafe52ba52181306ee620ee0

Note: This is the specification of the DPDHL Group Parcel DE Shipping API for Post & Parcel Germany. This REST web service allows business customers to create shipping labels on demand.


## Installation

```bash
composer require prefabcortex/dhl-parcel-de-shipping-v2
```

### HTTP client

This package talks to the API over PSR-18 and does not ship an HTTP client of its
own, so it never forces a second one on a project that already has one. If yours has
none yet, the command above stops before installing anything and lists the packages
that qualify — pick one:

```bash
composer require guzzlehttp/guzzle
```

`symfony/http-client` works just as well, and either is picked up automatically. To
use a client you configured yourself, hand it to `ClientConfig::withHttpClient()`
before building the client.

A client picked up automatically gets a timeout — ten seconds to connect, sixty for the
whole request — which `ClientConfig::withTimeout(Timeout::seconds(…))` changes. A client
you hand over keeps the timeouts you gave it.

## Choosing a server

`ClientConfig::forServer()` takes one of the servers the API description names. Each is named
after what the description says of it, and their order means nothing: pick the one your
credentials belong to.

| Server | URL | Description |
|---|---|---|
| `Server::ProductiveServer` | `https://api-eu.dhl.com/parcel/de/shipping/v2` | Productive Server |
| `Server::SandboxServer` | `https://api-sandbox.dhl.com/parcel/de/shipping/v2` | Sandbox Server |

```php
use Prefabcortex\DhlParcelDeShippingV2\ClientConfig;
use Prefabcortex\DhlParcelDeShippingV2\Server;

$config = ClientConfig::forServer(Server::ProductiveServer);
```

For a mock server or a proxy, `ClientConfig::forBaseUrl('https://mock.example.com')` sends
every request to one address.

## Quickstart

Every link below points at a file under `examples/` to read and copy from.

### Authentication

- **BasicAuth** (HTTP Basic): `Client::withBasicAuth($username, $password, $config)` — see [`examples/Auth/BasicAuth.php`](examples/Auth/BasicAuth.php)
- **ApiKey** (API key): `Client::withApiKey($apiKey, $config)` — see [`examples/Auth/ApiKey.php`](examples/Auth/ApiKey.php)
- **OAuth2** (OAuth2): `Client::withOAuth($token, $config)` — see [`examples/Auth/OAuth2.php`](examples/Auth/OAuth2.php)

For a scheme this package generates no authenticator for, or a signature the description cannot express, implement `Authentication\Authenticator` and pass it to `Client::withAuthenticators($config, ...$authenticators)`.

### Operations

#### General

- [`rootGet`](examples/Operations/General/RootGetExample.php)

#### Manifests

- [`getManifests`](examples/Operations/Manifests/GetManifestsExample.php)
- [`manifestsPost`](examples/Operations/Manifests/ManifestsPostExample.php)

#### ShipmentsAndLabels

- [`createOrders`](examples/Operations/ShipmentsAndLabels/CreateOrdersExample.php)
- [`getLabel`](examples/Operations/ShipmentsAndLabels/GetLabelExample.php)
- [`getOrder`](examples/Operations/ShipmentsAndLabels/GetOrderExample.php)
- [`ordersAccountDelete`](examples/Operations/ShipmentsAndLabels/OrdersAccountDeleteExample.php)

## Optional values

A property the description marks required is a plain type on the model. A property it
leaves optional is an `Option`, because there are three states to tell apart and not
two: the field was absent, the field was `null`, or the field had a value. A `?string`
can hold the last two and loses the first, and losing it matters — `toArray()` sends
back only what was actually set, so a field the server never mentioned stays unmentioned
rather than being echoed as `null`.

Three methods, and no others:

```php
$value->isDefined();        // was it there at all?
$value->get();              // the value — RuntimeException on an absent one
$value->getOrElse($other);  // the value, or $other when it is absent
```

On `BankAccount`, for instance, `getAccountHolder()` is required and reads as itself,
while `getBankName()` is optional:

```php
$model->getAccountHolder();            // the value
$model->getBankName()->getOrElse('');  // the value, or a default
```

Writing one is the other way round, and needs none of this. `builder()` takes the required
properties and starts every optional one absent; each `set…()` takes the bare value and
wraps it for you, and `build()` hands back the model. `toBuilder()` starts from a model you
already have, which itself never changes. Query and header parameter objects take bare
values in their `with…()` the same way. There is no reason to write `Some::create()` or
`None::create()` yourself.

## Response validation

The API description says what the API *should* send. By default every response is held
to it: each constraint the description declares — a maximum length, a pattern, a range,
the values an enum allows, a property that must not be `null` — is checked before the
response is read, and a response that breaks one raises `ResponseValidationException`.
That way a difference between the API and its description shows up the first time it
happens, not somewhere further down your code.

Sometimes the API is the one that does not keep to its description. When it is, and you
need to go on using it until its provider fixes that, read responses without these
checks:

```php
use Prefabcortex\DhlParcelDeShippingV2\Http\ResponseValidation;

$config = $config->withResponseValidation(ResponseValidation::Lax);
```

| The response carries                        | `Strict` (default)            | `Lax`                        |
|---------------------------------------------|-------------------------------|------------------------------|
| a text too long, a pattern or range not met | `ResponseValidationException` | read as it is                |
| `null` in an optional property              | `ResponseValidationException` | read as absent               |
| an enum value no case of its enum names     | `ResponseValidationException` | read as `UnknownEnumValue`   |
| a value its model cannot hold               | `ResponseValidationException` | `MalformedResponseException` |

An API adds a value to an enum — a new status, a new service — before this package is
regenerated. That is why every enum property of a model is typed `SomeEnum|UnknownEnumValue`:
under `Lax` the value is kept, and `->value` reads it on either. A `match` over the enum
then needs a `default` arm, which is the point — it is where your code decides what a
value it has never seen means.

A value its model cannot hold — a wrong type, a missing required property — stays an
error either way, because a typed model has nowhere to put it. Requests are not affected
by the setting — what you send is always checked in full before it leaves, and the
builders take only the cases an enum has.

Both exceptions are `ResponseException`s, so `getResponse()` and `getRawResponse()` hand
back what arrived. Mind what that means: **the request reached the API**, and whatever it
asked for may have happened — a shipment created, an order placed. Do not simply send it
again; read the raw response first.

## Error handling

Every exception a call can end in implements `ApiException`, so one catch covers
everything the API can report:

```php
try {
    // … any operation
} catch (ApiException $e) {
    // …
}
```

That includes the call never completing. A failure your HTTP client reports — a
timeout, a refused connection, a request it would not send — is caught and re-thrown as
`TransportException`, so it lands in the same catch as everything else rather than
beside it.

Two things follow, and both are deliberate. The PSR interfaces still match, so
`catch (Psr\Http\Client\NetworkExceptionInterface $e)` before the block above still
picks out the failures worth retrying, and `getRequest()` tells you what was being
sent. But a catch written against your client's *own* class — Guzzle's
`ConnectException`, say — does not match, because the exception you receive is not that
class. The original is there as `getPrevious()`.

One exception stays outside the catch on purpose, because it is a mistake in the
calling code rather than a way the call can fail: `get()` on an `Option` that holds no
value throws `RuntimeException`. Ask `isDefined()` first, or use `getOrElse()`.

Below it the hierarchy narrows:

- `ResponseException` — raised by a response that arrived, and where
  `getResponse()` and `getRawResponse()` are declared. `MalformedDataException`,
  `NoHttpClientException`, `UnsupportedValueException` and `ValidationException` have
  none to hand back and stop at `ApiException`.
- `ClientException` (4xx) and `ServerException` (5xx) — which side the failure came from.
- One class per status: `BadRequestException`, `UnauthorizedException`,
  `NotFoundException`, `TooManyRequestsException`, `InternalServerErrorException`.
- One class per operation and status — these are the ones actually thrown. Each adds the
  typed error body:
  `CreateOrdersBadRequestException::getLabelDataResponse(): LabelDataResponse`.

A response that fits no declared branch is one of two `ResponseException`s with nothing
beyond the two accessors, named after what did not fit:

- `UnexpectedStatusCodeException` — no response declares the status, or a declared error
  response's body does not read as declared (the reason is `getPrevious()`). Its status
  still says which side failed: a 4xx is a `ClientException`, a 5xx a `ServerException`.
- `UnexpectedContentTypeException` — the status is declared, but not under the content
  type the response arrived with. A 404 served as `text/html` by a proxy in front of the
  API is this, not a `NotFoundException`: its body is not the one the description
  promises, so there is nothing to read it into.

Two things an error response carries are read from any `ResponseException`, whether or
not the description declares its status. A 429 or a 503 often says how long to wait in
a `Retry-After` header, as a number of seconds or as a date; `RetryAfter` reads either.
A body served as `application/problem+json` (RFC 9457) reads as `ProblemDetails`:

```php
use Prefabcortex\DhlParcelDeShippingV2\Exception\ClientException;
use Prefabcortex\DhlParcelDeShippingV2\Http\ProblemDetails;
use Prefabcortex\DhlParcelDeShippingV2\Http\RetryAfter;

try {
    // … any operation
} catch (ClientException $e) {
    $problem = ProblemDetails::of($e);
    if ($problem->isDefined()) {
        error_log($problem->get()->detail->getOrElse($e->getMessage()));
    }

    $retryAfter = RetryAfter::of($e);
    if ($retryAfter->isDefined()) {
        sleep($retryAfter->get()->secondsFrom(new DateTimeImmutable()));
    }
}
```

## Versioning

This package carries its own SemVer line. The API version shown above is *provenance*,
not the package version — it is recorded in `.prefabcortex-generation.json` along with
the checksum of the specification it was generated from.

How far a release counts is measured, not chosen:
`roave/backward-compatibility-check` compares it against the tag before it.

| The check reports | Release |
| --- | --- |
| a break in the public API | **major** — `2.4.1` becomes `3.0.0` |
| no break | **minor** — `2.4.1` becomes `2.5.0` |

There are no patch releases. The check finds breaks, not additions, so anything that
changed without breaking is released as a minor — and a constraint such as `^2.4` never
installs a break.

Symbols marked `@internal` are excluded from all of this. They are the package's
plumbing — the transport classes under `Http/` and `Operation/`, the `to…Parameters()`
conversions — and they may change in any release. Everything else is the contract,
including what code outside the package implements or calls: the validation rules and
the types a `custom-query-resolver` works with.

## Trademarks

Trademarks mentioned here — including in the name of this package — are the
property of their respective owners. They appear to identify the API this client
addresses, and for no other purpose: nothing here is a claim about who made this
package or who stands behind it.

This is an unofficial client, generated from the published API description. It IS
NOT affiliated with, endorsed by, or connected to the operator of that API.

## License

The generated code is 0BSD — see [LICENSE](LICENSE). Documentation text carried over
from the API description is quoted from that description and remains its author's;
it is reproduced here to document the interface, not relicensed. Holding the rights to
that description is the responsibility of this package's publisher, the copyright
holder named in [LICENSE](LICENSE).

## About This Package

This package was generated from an OpenAPI specification. It is yours to take
further — bear in mind only that regenerating it replaces every file in the
package, so anything changed by hand is worth keeping somewhere that survives
that: a patch, a subclass, or a fork you maintain yourself.

Generated by [PrefabCortex](https://www.prefabcortex.com), which turns an OpenAPI
specification into a ready-to-use PHP Composer package. PrefabCortex
generated it on behalf of the publisher named in [LICENSE](LICENSE) and
neither publishes, reviews nor maintains it.
