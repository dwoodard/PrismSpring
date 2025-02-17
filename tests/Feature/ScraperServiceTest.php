<?php

use App\Services\Scraper;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\BrowserKit\CookieJar;
use ReflectionClass;

/**
 * Helper function to override the protected browser property.
 */
function setBrowser(Scraper $scraper, $mockBrowser): void
{
    $ref = new ReflectionClass($scraper);
    $property = $ref->getProperty('browser');
    $property->setAccessible(true);
    $property->setValue($scraper, $mockBrowser);
}

test('it should make a GET request and return a crawler', function () {
    // Create a dummy Crawler instance with basic HTML content.
    $dummyCrawler = new Crawler('<html><body><p>Hello World!</p></body></html>');

    // Create a mock of the HttpBrowser.
    $mockBrowser = $this->createMock(HttpBrowser::class);

    // Set up the expectation that the "request" method will be called once
    // with the specified parameters and will return the dummy Crawler.
    $mockBrowser->expects($this->once())
        ->method('request')
        ->with('GET', 'https://example.com', [], [], [])
        ->willReturn($dummyCrawler);

    // Instantiate the Scraper and inject the mocked HttpBrowser.
    $scraper = new Scraper();
    setBrowser($scraper, $mockBrowser);

    // Call the request method and assert that the returned crawler is the dummy instance.
    $result = $scraper->request('GET', 'https://example.com');
    expect($result)->toBe($dummyCrawler);
});


test('it should make a JSON request and return a crawler', function () {
    $dummyCrawler = new Crawler('<html></html>');
    $mockBrowser = $this->createMock(HttpBrowser::class);
    $mockBrowser->expects($this->once())
        ->method('jsonRequest')
        ->with('POST', 'https://example.com', ['key' => 'value'])
        ->willReturn($dummyCrawler);

    $scraper = new Scraper();
    setBrowser($scraper, $mockBrowser);

    $result = $scraper->jsonRequest('POST', 'https://example.com', ['key' => 'value']);
    expect($result)->toBe($dummyCrawler);
});

test('it should make an XML HTTP request and return a crawler', function () {
    $dummyCrawler = new Crawler('<html></html>');
    $mockBrowser = $this->createMock(HttpBrowser::class);
    $mockBrowser->expects($this->once())
        ->method('xmlHttpRequest')
        ->with('PUT', 'https://example.com', ['param' => '123'])
        ->willReturn($dummyCrawler);

    $scraper = new Scraper();
    setBrowser($scraper, $mockBrowser);

    $result = $scraper->xmlHttpRequest('PUT', 'https://example.com', ['param' => '123']);
    expect($result)->toBe($dummyCrawler);
});

test('it should click a link and return a crawler', function () {
    $dummyCrawler = new Crawler('<html></html>');
    $mockBrowser = $this->createMock(HttpBrowser::class);
    $mockBrowser->expects($this->once())
        ->method('clickLink')
        ->with('About Us', [])
        ->willReturn($dummyCrawler);

    $scraper = new Scraper();
    setBrowser($scraper, $mockBrowser);

    $result = $scraper->clickLink('About Us');
    expect($result)->toBe($dummyCrawler);
});

test('it should submit a form using button identifier and return a crawler', function () {
    $dummyCrawler = new Crawler('<html></html>');
    $mockBrowser = $this->createMock(HttpBrowser::class);
    $mockBrowser->expects($this->once())
        ->method('submitForm')
        ->with('Submit', ['name' => 'John Doe', 'email' => 'john@example.com'], null, [])
        ->willReturn($dummyCrawler);

    $scraper = new Scraper();
    setBrowser($scraper, $mockBrowser);

    $result = $scraper->submitForm('Submit', ['name' => 'John Doe', 'email' => 'john@example.com']);
    expect($result)->toBe($dummyCrawler);
});

test('it should submit a form object and return a crawler', function () {
    $dummyCrawler = new Crawler('<html></html>');
    $dummyForm = new stdClass(); // Placeholder for a form object.
    $mockBrowser = $this->createMock(HttpBrowser::class);
    $mockBrowser->expects($this->once())
        ->method('submit')
        ->with($dummyForm)
        ->willReturn($dummyCrawler);

    $scraper = new Scraper();
    setBrowser($scraper, $mockBrowser);

    $result = $scraper->submitFormObject($dummyForm);
    expect($result)->toBe($dummyCrawler);
});

test('it should retrieve the current HTTP response', function () {
    $dummyResponse = (object)['status' => 200];
    $mockBrowser = $this->createMock(HttpBrowser::class);
    $mockBrowser->expects($this->once())
        ->method('getResponse')
        ->willReturn($dummyResponse);

    $scraper = new Scraper();
    setBrowser($scraper, $mockBrowser);

    $result = $scraper->getResponse();
    expect($result)->toBe($dummyResponse);
});

test('it should set a cookie in the CookieJar', function () {
    $expiration = time() + 3600;
    $mockCookieJar = $this->createMock(CookieJar::class);
    $mockBrowser = $this->createMock(HttpBrowser::class);
    $mockBrowser->expects($this->once())
        ->method('getCookieJar')
        ->willReturn($mockCookieJar);
    $mockCookieJar->expects($this->once())
        ->method('set')
        ->with($this->callback(function ($cookie) use ($expiration) {
            return $cookie instanceof Cookie &&
                $cookie->getName() === 'user' &&
                $cookie->getValue() === 'john_doe' &&
                $cookie->getExpiresTime() === $expiration;
        }));

    $scraper = new Scraper();
    setBrowser($scraper, $mockBrowser);

    $scraper->setCookie('user', 'john_doe', $expiration);
    expect(true)->toBeTrue(); // Method call confirmed.
});

test('it should navigate back and return a crawler', function () {
    $dummyCrawler = new Crawler('<html></html>');
    $mockBrowser = $this->createMock(HttpBrowser::class);
    $mockBrowser->expects($this->once())
        ->method('back')
        ->willReturn($dummyCrawler);

    $scraper = new Scraper();
    setBrowser($scraper, $mockBrowser);

    $result = $scraper->back();
    expect($result)->toBe($dummyCrawler);
});

test('it should navigate forward and return a crawler', function () {
    $dummyCrawler = new Crawler('<html></html>');
    $mockBrowser = $this->createMock(HttpBrowser::class);
    $mockBrowser->expects($this->once())
        ->method('forward')
        ->willReturn($dummyCrawler);

    $scraper = new Scraper();
    setBrowser($scraper, $mockBrowser);

    $result = $scraper->forward();
    expect($result)->toBe($dummyCrawler);
});

test('it should restart the browser', function () {
    $mockBrowser = $this->createMock(HttpBrowser::class);
    $mockBrowser->expects($this->once())
        ->method('restart');

    $scraper = new Scraper();
    setBrowser($scraper, $mockBrowser);

    $scraper->restart();
    expect(true)->toBeTrue(); // Confirm that restart was called.
});
