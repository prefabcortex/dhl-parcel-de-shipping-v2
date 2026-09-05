# Parcel DE Shipping API (Post & Parcel Germany)

## API Information

- **Title:** Parcel DE Shipping API (Post & Parcel Germany)
- **Version:** 2.1.14
- **Source Spec SHA-256:** 0eeb4d9ea2227966a9b4d04de6f4064d3ec2ce52c8bdbaf9bb9c310cf0975948

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

## Quickstart

Every link below points at a file under `examples/` to read and copy from.

### Authentication

- **BasicAuth** (HTTP Basic): `Client::withBasicAuth(..., $config)` — see [`examples/Auth/BasicAuth.php`](examples/Auth/BasicAuth.php)
- **ApiKey** (API key): `Client::withApiKey(..., $config)` — see [`examples/Auth/ApiKey.php`](examples/Auth/ApiKey.php)
- **OAuth2** (OAuth2): `Client::withOAuth(..., $config)` — see [`examples/Auth/OAuth2.php`](examples/Auth/OAuth2.php)

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

## Error handling

Every exception this package raises itself implements `ApiException`, so one catch
covers everything the API can report:

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
`ConnectException`, say — no longer matches, because the exception you now receive is
not that class. The original is still there as `getPrevious()`.

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

A response that fits no declared branch comes out as `UnexpectedStatusCodeException`,
a `ResponseException` with nothing beyond the two accessors — nothing about it was
declared. That includes a response whose status *is* declared but whose content type is
not: the match tests both, so a 404 arriving as `text/html` from a proxy in front of the
API is not a `NotFoundException`.

## Versioning

This package carries its own SemVer line. The API version shown above is *provenance*,
not the package version — it is recorded in `.prefabcortex-generation.json` along with
the checksum of the specification it was generated from.

Composer reads the version from the Git tag, which is why this `composer.json` declares
none. A release is a tag:

```bash
git init && git add -A && git commit -m "Initial generation" && git tag 1.0.0
```

For every later release, let the check decide how far to count:

```bash
vendor/bin/roave-backward-compatibility-check
```

It compares the last `x.y.z` tag against `HEAD` and exits non-zero when the public API
broke.

| Result | Next version |
| --- | --- |
| breaks reported | **major** — `2.4.1` becomes `3.0.0` |
| no breaks, but `src/` changed | **minor** — `2.4.1` becomes `2.5.0` |
| `src/` unchanged | **patch**, or no release at all |

The middle row is deliberately conservative: the check finds breaks, not additions, so
anything that changed without breaking is treated as a minor.

Symbols marked `@internal` are excluded from all of this. They are the package's
plumbing — the transport classes under `Http/` and `Operation/`, the generated
validation rules, the `to…Parameters()` conversions — and they may change in any
release. Everything else is the contract.

In CI, check out with `fetch-depth: 0`. Without the tags the tool has nothing to
compare against.

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
it is reproduced here to document the interface, not relicensed. Whoever publishes
this package is responsible for holding the rights to the description it was
generated from.

## About This Package

This package was generated from an OpenAPI specification. It is yours to take
further — bear in mind only that regenerating it replaces every file in the
package, so anything changed by hand is worth keeping somewhere that survives
that: a patch, a subclass, or a fork you maintain yourself.

Generated by [PrefabCortex](https://www.prefabcortex.com), which turns an OpenAPI
specification into a ready-to-use PHP Composer package.
