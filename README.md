# BMD Data Fetch

[![PHP version](https://d25lcipzij17d.cloudfront.net/badge.svg?id=ph&r=r&type=6e&v=1.0.5&x2=2)](https://packagist.org/packages/saidy/bmd-station-data-fetch)

To fetch BMD real-time data from [BMD website](https://live3.bmd.gov.bd).

## Examples

```php

use Saidy\BmdStationDataFetch\BmdDataFetch;

// Returns list of all station data by BMD
BmdDataFetch::getStationList();

// Returns an array of station data By Station code. Station code is available in BmdDataFetch::getStationList() -> code
BmdDataFetch::getStationData("41923")

```

### License

This project is licensed under the terms of the MIT license.
