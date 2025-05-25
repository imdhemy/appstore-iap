# Upgrade from 1.0 to 2.0

## Client Factory

- The `ClientFactory::create()` & `ClientFactory:createSandbox()` methods are removed, use the specific factory methods
  instead.
- The `ClientFactory::BASE_URI` & `ClientFactory::SANDBOX_BASE_URI` constants are removed, use the specific constants
  instead.

## Value objects

- Use `\Imdhemy\AppStore\ValueObjects\Time::toCarbon` instead of `\Imdhemy\AppStore\ValueObjects\Time::getCarbon`  
