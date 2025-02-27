English / [日本語](README.ja.md)

URL Standard
============
Makes the algorithms and APIs defined by [URL Standard] available on PHP.

[URL Standard]: https://url.spec.whatwg.org/ "The URL Standard defines URLs, domains, IP addresses, the application/x-www-form-urlencoded format, and their API."

Description
-----------
URL Standard is the Web standard specification that replaces the previous standards [RFC 3986] and [RFC 3987].

The specification defines [URL interface] and [URLSearchPrams interface] as [API].
This library allows you to use [esperecyan\url\URL class] as [URL interface] and [esperecyan\url\URLSearchParams class] as [URLSearchPrams interface].
The documents on MDN may be easy to understand by way of explanation of the interfaces https://developer.mozilla.org/docs/Web/API/URL https://developer.mozilla.org/docs/Web/API/URLSearchParams .

This library allows you to use the algorithms defined by URL Standard. For details, see [The correspondence table of the algorithms].

[RFC 3986]: https://tools.ietf.org/html/rfc3986 "A Uniform Resource Identifier (URI) is a compact sequence of characters that identifies an abstract or physical resource. This specification defines the generic URI syntax and a process for resolving URI references that might be in relative form, along with guidelines and security considerations for the use of URIs on the Internet."
[RFC 3987]: https://tools.ietf.org/html/rfc3987 "This document defines a new protocol element, the Internationalized Resource Identifier (IRI), as a complement to the Uniform Resource Identifier (URI)."
[API]: https://url.spec.whatwg.org/#api "URL Standard defines URL’s existing JavaScript API in full detail and add enhancements to make it easier to work with."
[URL interface]: https://url.spec.whatwg.org/#url "URL Standard adds a new URL object as well for URL manipulation without usage of HTML elements. (Useful for JavaScript worker environments.)"
[URLSearchPrams interface]: https://url.spec.whatwg.org/#interface-urlsearchparams
[esperecyan\url\URL class]: https://esperecyan.github.io/url/class-esperecyan.url.URL
[esperecyan\url\URLSearchParams class]: https://esperecyan.github.io/url/class-esperecyan.url.URLSearchParams
[The correspondence table of the algorithms]: #the-correspondence-table-of-the-algorithms

Example
-------
```php
<?php
require_once 'vendor/autoload.php';

use esperecyan\url\URL;

$url = new URL('http://url.test/foobar?name=value');
var_dump($url->protocol, $url->pathname, $url->searchParams->get('name'));
```

The above example will output:

```plain
string(5) "http:"
string(7) "/foobar"
string(5) "value"
```

Requirement
-----------
* PHP 5.4 or later **(PHP 5.4 and 5.5 are deprecated)**
	+ SPL Types PECL library is not supported
* [Intl extension module]

[Intl extension module]: https://secure.php.net/intl "Internationalization extension (is referred as Intl) is a wrapper for ICU library, enabling PHP programmers to perform UCA-conformant collation and date/time/number/currency formatting in their scripts. "

Install
-------
```sh
composer require esperecyan/url
```

For help with installation of Composer, see [Composer documentation].

[Composer documentation]: https://getcomposer.org/doc/00-intro.md "Composer is a tool for dependency management in PHP. It allows you to declare the dependent libraries your project needs and it will install them in your project for you."

Contribution
------------
1. Fork it ( https://github.com/esperecyan/url )
2. Create your feature branch `git checkout -b my-new-feature`
3. Commit your changes `git commit -am 'Add some feature'`
4. Push to the branch `git push origin my-new-feature`
5. Create new Pull Request

Or

Create new Issue

If you find any mistakes of English in the README or Doc comments or any flaws in tests, please report by such as above means.
I also welcome translations of README too.

Acknowledgement
---------------
I use the code from [コードポイントから UTF-8 の文字を生成する - Qiita] and [UTF-8 の文字からコードポイントを求める - Qiita] in implementing [URLencoding class].

I use [URL Standard (Japanese translation)] as reference in creating this library.

HADAA helped me translate README to English.

[URLencoding class]: src/lib/URLencoding.php
[コードポイントから UTF-8 の文字を生成する - Qiita]: http://qiita.com/masakielastic/items/68f81e1b7d153ee5cc81 "バリデーションの際に想定外の文字が通っていないか調べるには Unicode で定義されるすべての文字を試すことが必要です。UTF-8 の場合、コードポイントの範囲は U+0000 から U+7FFF、U+E000 から U+10FFFF までです。"
[UTF-8 の文字からコードポイントを求める - Qiita]: http://qiita.com/masakielastic/items/5696cf90738c1438f10d "文字の Unicode プロパティやエンコーディングに関する情報を検索で調べる際にコードポイントが必要になることがあります。PHP 5.5 で intl 拡張モジュールに IntlCodePointBreakIterator が追加され、コードポイントを求めやすくなりました。"
[URL Standard (Japanese translation)]: https://triple-underscore.github.io/URL-ja.html "このページ は、 WHATWG による，副題の日付 時点の URL Standard を日本語に翻訳したものです。 この翻訳の正確性は保証されません。 この仕様の公式な文書は英語版であり、この日本語訳は公式のものではありません。"

Semantic Versioning
-------------------
This library uses [Semantic Versioning].
The classes, methods, constants, and properties in [the documentation of the library] are the public API. 

[Semantic Versioning]: http://semver.org/
[the documentation of the library]: https://esperecyan.github.io/webidl/

Licence
-------
This library is licensed under the [Mozilla Public License Version 2.0] \(MPL-2.0).

[Mozilla Public License Version 2.0]: https://www.mozilla.org/MPL/2.0/

The correspondence table of the algorithms
------------------------------------------
| [1. Infrastructure]             |                                                                    |
|---------------------------------|--------------------------------------------------------------------|
| [percent encode]                | [esperecyan\url\lib\Infrastructure::percentEncode()]               |
| [percent decode]                | [esperecyan\url\lib\Infrastructure::percentDecode()]               |
| [C0 control percent-encode set] | [esperecyan\url\lib\Infrastructure::C0_CONTROL_PERCENT_ENCODE_SET] |
| [path percent-encode set]       | [esperecyan\url\lib\Infrastructure::PATH_PERCENT_ENCODE_SET]       |
| [userinfo percent-encode set]   | [esperecyan\url\lib\Infrastructure::USERINFO_PERCENT_ENCODE_SET]   |
| [utf-8 percent encode]          | [esperecyan\url\lib\Infrastructure::utf8PercentEncode()]           |

| [3. Hosts (domains and IP addresses)]     |                                                                 |
|-------------------------------------------|-----------------------------------------------------------------|
| [domain]<br>[opaque host]<br>[empty host] | A valid utf-8 string                                            |
| [IPv4 address]                            | An integer or float in the range 0 to 0xFFFFFFFF                |
| [IPv6 address]                            | An array with 8 elements of an integer in the range 0 to 0xFFFF |
| [forbidden host code point]               | [esperecyan\url\lib\HostProcessing::FORBIDDEN_HOST_CODE_POINTS] |
| [domain to ASCII]                         | [esperecyan\url\lib\HostProcessing::domainToASCII()]            |
| [domain to Unicode]                       | [esperecyan\url\lib\HostProcessing::domainToUnicode()]          |
| [valid domain]                            | [esperecyan\url\lib\HostProcessing::isValidDomain()]            |
| [host parser]                             | [esperecyan\url\lib\HostProcessing::parseHost()]                |
| [IPv4 number parser]                      | [esperecyan\url\lib\HostProcessing::parseIPv4Number()]          |
| [IPv4 parser]                             | [esperecyan\url\lib\HostProcessing::parseIPv4()]                |
| [IPv6 parser]                             | [esperecyan\url\lib\HostProcessing::parseIPv6()]                |
| [opaque-host parser]                      | [esperecyan\url\lib\HostProcessing::parseOpaqueHost()]          |
| [host serializer]                         | [esperecyan\url\lib\HostProcessing::serializeHost()]            |
| [IPv4 serializer]                         | [esperecyan\url\lib\HostProcessing::serializeIPv4()]            |
| [IPv6 serializer]                         | [esperecyan\url\lib\HostProcessing::serializeIPv6()]            |

| [4. URLs]                              |                                                                |
|----------------------------------------|----------------------------------------------------------------|
| [URL]                                  | An instance of [esperecyan\url\lib\URL class]                  |
| [scheme]                               | [esperecyan\url\lib\URL->scheme]                               |
| [username]                             | [esperecyan\url\lib\URL->username]                             |
| [password]                             | [esperecyan\url\lib\URL->password]                             |
| [host]                                 | [esperecyan\url\lib\URL->host]                                 |
| [port]                                 | [esperecyan\url\lib\URL->port]                                 |
| [path]                                 | [esperecyan\url\lib\URL->path]                                 |
| [query]                                | [esperecyan\url\lib\URL->query]                                |
| [fragment]                             | [esperecyan\url\lib\URL->fragment]                             |
| [cannot-be-a-base-URL flag]            | [esperecyan\url\lib\URL->cannotBeABaseURLFlag]                 |
| [object]                               | [esperecyan\url\lib\URL->object]                               |
| [special scheme]                       | [esperecyan\url\lib\URL::$specialSchemes]                      |
| [is special]                           | [esperecyan\url\lib\URL->isSpecial()]                          |
| [includes credentials]                 | [esperecyan\url\lib\URL->isIncludingCredentials()]             |
| [cannot have a username/password/port] | [esperecyan\url\lib\URL->cannotHaveUsernamePasswordPort()]     |
| [Windows drive letter]                 | [esperecyan\url\lib\URL::WINDOWS_DRIVE_LETTER]                 |
| [normalized Windows drive letter]      | [esperecyan\url\lib\URL::NORMALIZED_WINDOWS_DRIVE_LETTER]      |
| [starts with a Windows drive letter]   | [esperecyan\url\lib\URL::stringStartsWithWindowsDriveLetter()] |
| [shorten a path]                       | [esperecyan\url\lib\URL->shortenPath()]                        |
| [single-dot path segment]              | [esperecyan\url\lib\URL::SINGLE_DOT_PATH_SEGMENT]              |
| [double-dot path segment]              | [esperecyan\url\lib\URL::DOUBLE_DOT_PATH_SEGMENT]              |
| [URL code points]                      | [esperecyan\url\lib\URL::URL_CODE_POINTS]                      |
| [URL parser]                           | [esperecyan\url\lib\URL::parseURL()]                           |
| [basic URL parser]                     | [esperecyan\url\lib\URL::parseBasicURL()]                      |
| [set the username]                     | [esperecyan\url\lib\URL->setUsername()]                        |
| [set the password]                     | [esperecyan\url\lib\URL->setPassword()]                        |
| [URL serializer]                       | [esperecyan\url\lib\URL->serializeURL()]                       |
| [origin]                               | [esperecyan\url\lib\URL->getOrigin()]                          |

| [5. application/x-www-form-urlencoded]              |                                                            |
|-----------------------------------------------------|------------------------------------------------------------|
| [application/x-www-form-urlencoded parser]          | [esperecyan\url\lib\URLencoding::parseURLencoded()]        |
| [application/x-www-form-urlencoded byte serializer] | [esperecyan\url\lib\URLencoding::serializeURLencodedByte()]|
| [application/x-www-form-urlencoded serializer]      | [esperecyan\url\lib\URLencoding::serializeURLencoded()]    |
| [application/x-www-form-urlencoded string parser]   | [esperecyan\url\lib\URLencoding::parseURLencodedString()]  |
| name-value or [name-value-type tuples]              | An array of two-element or three-element arrays with the first element the name, the second the value, and the third the type. The value is an array with the value for `name` key as the name |

[1. Infrastructure]: https://url.spec.whatwg.org/#infrastructure
[percent encode]: https://url.spec.whatwg.org/#percent-encode
[percent decode]: https://url.spec.whatwg.org/#percent-decode
[C0 control percent-encode set]: https://url.spec.whatwg.org/#c0-control-percent-encode-set
[path percent-encode set]: https://url.spec.whatwg.org/#path-percent-encode-set
[userinfo percent-encode set]: https://url.spec.whatwg.org/#userinfo-percent-encode-set
[utf-8 percent encode]: https://url.spec.whatwg.org/#utf_8-percent-encode

[3. Hosts (domains and IP addresses)]: https://url.spec.whatwg.org/hosts-(domains-and-ip-addresses)
[domain]: https://url.spec.whatwg.org/#concept-domain
[opaque host]: https://url.spec.whatwg.org/#opaque-host
[empty host]: https://url.spec.whatwg.org/#empty-host
[IPv4 address]: https://url.spec.whatwg.org/#concept-ipv4
[IPv6 address]: https://url.spec.whatwg.org/#concept-ipv6
[forbidden host code point]: https://url.spec.whatwg.org/#forbidden-host-code-point
[domain to ASCII]: https://url.spec.whatwg.org/#concept-domain-to-ascii
[domain to Unicode]: https://url.spec.whatwg.org/#concept-domain-to-unicode
[valid domain]: https://url.spec.whatwg.org/#valid-domain
[host parser]: https://url.spec.whatwg.org/#concept-host-parser
[IPv4 number parser]: https://url.spec.whatwg.org/#ipv4-number-parser
[IPv4 parser]: https://url.spec.whatwg.org/#concept-ipv4-parser
[IPv6 parser]: https://url.spec.whatwg.org/#concept-ipv6-parser
[opaque-host parser]: https://url.spec.whatwg.org/#concept-opaque-host-parser
[host serializer]: https://url.spec.whatwg.org/#concept-host-serializer
[IPv4 serializer]: https://url.spec.whatwg.org/#concept-ipv4-serializer
[IPv6 serializer]: https://url.spec.whatwg.org/#concept-ipv6-serializer

[4. URLs]: https://url.spec.whatwg.org/#urls
[URL]: https://url.spec.whatwg.org/#concept-url
[scheme]: https://url.spec.whatwg.org/#concept-url-scheme
[username]: https://url.spec.whatwg.org/#concept-url-username
[password]: https://url.spec.whatwg.org/#concept-url-password
[host]: https://url.spec.whatwg.org/#concept-url-host
[port]: https://url.spec.whatwg.org/#concept-url-port
[path]: https://url.spec.whatwg.org/#concept-url-path
[query]: https://url.spec.whatwg.org/#concept-url-query
[fragment]: https://url.spec.whatwg.org/#concept-url-fragment
[cannot-be-a-base-URL flag]: https://url.spec.whatwg.org/#url-cannot-be-a-base-url-flag
[object]: https://url.spec.whatwg.org/#concept-url-object
[special scheme]: https://url.spec.whatwg.org/#special-scheme
[is special]: https://url.spec.whatwg.org/#is-special
[includes credentials]: https://url.spec.whatwg.org/#include-credentials
[cannot have a username/password/port]: https://url.spec.whatwg.org/#cannot-have-a-username-password-port
[Windows drive letter]: https://url.spec.whatwg.org/#windows-drive-letter
[normalized Windows drive letter]: https://url.spec.whatwg.org/#normalized-windows-drive-letter
[shorten a path]: https://url.spec.whatwg.org/#shorten-a-urls-path
[starts with a Windows drive letter]: https://url.spec.whatwg.org/#start-with-a-windows-drive-letter
[single-dot path segment]: https://url.spec.whatwg.org/#syntax-url-path-segment-dot
[double-dot path segment]: https://url.spec.whatwg.org/#syntax-url-path-segment-dotdot
[URL code points]: https://url.spec.whatwg.org/#url-code-points
[URL parser]: https://url.spec.whatwg.org/#concept-url-parser
[basic URL parser]: https://url.spec.whatwg.org/#concept-basic-url-parser
[set the username]: https://url.spec.whatwg.org/#set-the-username
[set the password]: https://url.spec.whatwg.org/#set-the-password
[URL serializer]: https://url.spec.whatwg.org/#concept-url-serializer
[origin]: https://url.spec.whatwg.org/#concept-url-origin

[5. application/x-www-form-urlencoded]: https://url.spec.whatwg.org/#application/x-www-form-urlencoded
[application/x-www-form-urlencoded parser]: https://url.spec.whatwg.org/#concept-urlencoded-parser
[application/x-www-form-urlencoded byte serializer]: https://url.spec.whatwg.org/#concept-urlencoded-byte-serializer
[application/x-www-form-urlencoded serializer]: https://url.spec.whatwg.org/#concept-urlencoded-serializer
[application/x-www-form-urlencoded string parser]: https://url.spec.whatwg.org/#concept-urlencoded-string-parser
[name-value-type tuples]: https://html.spec.whatwg.org/multipage/forms.html#concept-input-type-file-selected

[esperecyan\url\lib\Infrastructure::percentEncode()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.Infrastructure#_percentEncode
[esperecyan\url\lib\Infrastructure::percentDecode()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.Infrastructure#_percentDecode
[esperecyan\url\lib\Infrastructure::C0_CONTROL_PERCENT_ENCODE_SET]: https://esperecyan.github.io/url/class-esperecyan.url.lib.Infrastructure#C0_CONTROL_PERCENT_ENCODE_SET
[esperecyan\url\lib\Infrastructure::PATH_PERCENT_ENCODE_SET]: https://esperecyan.github.io/url/class-esperecyan.url.lib.Infrastructure#PATH_PERCENT_ENCODE_SET
[esperecyan\url\lib\Infrastructure::USERINFO_PERCENT_ENCODE_SET]: https://esperecyan.github.io/url/class-esperecyan.url.lib.Infrastructure#USERINFO_PERCENT_ENCODE_SET
[esperecyan\url\lib\Infrastructure::utf8PercentEncode()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.Infrastructure#_utf8PercentEncode
[esperecyan\url\lib\HostProcessing::FORBIDDEN_HOST_CODE_POINTS]: https://esperecyan.github.io/url/class-esperecyan.url.lib.HostProcessing#FORBIDDEN_HOST_CODE_POINTS
[esperecyan\url\lib\HostProcessing::domainToASCII()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.HostProcessing#_domainToASCII
[esperecyan\url\lib\HostProcessing::domainToUnicode()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.HostProcessing#_domainToUnicode
[esperecyan\url\lib\HostProcessing::isValidDomain()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.HostProcessing#_isValidDomain
[esperecyan\url\lib\HostProcessing::parseHost()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.HostProcessing#_parseHost
[esperecyan\url\lib\HostProcessing::parseIPv4Number()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.HostProcessing#_parseIPv4Number
[esperecyan\url\lib\HostProcessing::parseIPv4()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.HostProcessing#_parseIPv4
[esperecyan\url\lib\HostProcessing::parseIPv6()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.HostProcessing#_parseIPv6
[esperecyan\url\lib\HostProcessing::parseOpaqueHost()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.HostProcessing#_parseOpaqueHost
[esperecyan\url\lib\HostProcessing::serializeHost()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.HostProcessing#_serializeHost
[esperecyan\url\lib\HostProcessing::serializeIPv4()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.HostProcessing#_serializeIPv4
[esperecyan\url\lib\HostProcessing::serializeIPv6()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.HostProcessing#_serializeIPv6
[esperecyan\url\lib\URL class]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL
[esperecyan\url\lib\URL->scheme]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#$scheme
[esperecyan\url\lib\URL->schemeData]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#$schemeData
[esperecyan\url\lib\URL->username]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#$username
[esperecyan\url\lib\URL->password]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#$password
[esperecyan\url\lib\URL->host]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#$host
[esperecyan\url\lib\URL->port]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#$port
[esperecyan\url\lib\URL->path]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#$path
[esperecyan\url\lib\URL->query]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#$query
[esperecyan\url\lib\URL->fragment]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#$fragment
[esperecyan\url\lib\URL->cannotBeABaseURLFlag]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#$cannotBeABaseURLFlag
[esperecyan\url\lib\URL->object]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#$object
[esperecyan\url\lib\URL::$specialSchemes]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#$specialSchemes
[esperecyan\url\lib\URL->isSpecial()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#_isSpecial
[esperecyan\url\lib\URL->isIncludingCredentials()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#_isIncludingCredentials
[esperecyan\url\lib\URL->cannotHaveUsernamePasswordPort()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#_cannotHaveUsernamePasswordPort
[esperecyan\url\lib\URL::WINDOWS_DRIVE_LETTER]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#WINDOWS_DRIVE_LETTER
[esperecyan\url\lib\URL::NORMALIZED_WINDOWS_DRIVE_LETTER]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#NORMALIZED_WINDOWS_DRIVE_LETTER
[esperecyan\url\lib\URL::stringStartsWithWindowsDriveLetter()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#_stringStartsWithWindowsDriveLetter
[esperecyan\url\lib\URL->shortenPath()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#_shortenPath
[esperecyan\url\lib\URL::SINGLE_DOT_PATH_SEGMENT]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#SINGLE_DOT_PATH_SEGMENT
[esperecyan\url\lib\URL::DOUBLE_DOT_PATH_SEGMENT]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#DOUBLE_DOT_PATH_SEGMENT
[esperecyan\url\lib\URL::URL_CODE_POINTS]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#URL_CODE_POINTS
[esperecyan\url\lib\URL::parseURL()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#_parseURL
[esperecyan\url\lib\URL::parseBasicURL()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#_parseBasicURL
[esperecyan\url\lib\URL->setUsername()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#_setUsername
[esperecyan\url\lib\URL->setPassword()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#_setPassword
[esperecyan\url\lib\URL->serializeURL()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#_serializeURL
[esperecyan\url\lib\URL->getOrigin()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URL#_getOrigin
[esperecyan\url\lib\URLencoding::parseURLencoded()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URLencoding#_parseURLencoded
[esperecyan\url\lib\URLencoding::serializeURLencodedByte()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URLencoding#_serializeURLencodedByte
[esperecyan\url\lib\URLencoding::serializeURLencoded()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URLencoding#_serializeURLencoded
[esperecyan\url\lib\URLencoding::parseURLencodedString()]: https://esperecyan.github.io/url/class-esperecyan.url.lib.URLencoding#_parseURLencodedString
