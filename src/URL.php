<?php

namespace NGSOFT\URL;

use Closure;
use esperecyan\webidl\{
    TypeError, TypeHinter
};
use InvalidArgumentException,
    JsonSerializable;
use NGSOFT\URL\lib\{
    HostProcessing, URLencoding
};
use Psr\Http\Message\UriInterface;

/**
 * The URL class represent an object providing static methods used for creating object URLs.
 * @link https://url.spec.whatwg.org/#URL URL Standard
 * @link https://developer.mozilla.org/docs/Web/API/URL URL - Web API Interfaces | MDN
 * @property string $href Is a USVString containing the whole URL.
 * @property-read string $origin
 *      Returns a USVString containing the canonical form of the origin of the specific location.
 * @property string $protocol Is a USVString containing the protocol scheme of the URL, including the final ‘:’.
 * @property string $username Is a USVString containing the username specified before the domain name.
 * @property string $password Is a USVString containing the password specified before the domain name.
 * @property string $host Is a USVString containing the host, that is the hostname,
 *      and then, if the port of the URL is not empty (which can happen because it was not specified
 *          or because it was specified to be the default port of the URL’s scheme), a ‘:’, and the port of the URL.
 * @property string $hostname Is a USVString containing the domain of the URL.
 * @property string $port Is a USVString containing the port number of the URL.
 * @property string $pathname Is a USVString containing an initial ‘/’ followed by the path of the URL.
 * @property string $search Is a USVString containing a ‘?’ followed by the parameters of the URL.
 * @property-read URLSearchParams $searchParams
 *      Returns a URLSearchParams object allowing to access the GET query arguments contained in the URL.
 * @property string $hash Is a USVString containing a ‘#’ followed by the fragment identifier of the URL.
 */
class URL implements JsonSerializable, UriInterface {

    /**
     * @var lib\URL An associated URL.
     * @link https://url.spec.whatwg.org/#concept-url-url URL Standard
     */
    private $url;

    /**
     * @var URLSearchParams An associated query object.
     * @link https://url.spec.whatwg.org/#concept-url-query-object URL Standard
     */
    private $queryObject;

    /**
     * The constructor returns a newly created URL object representing the URL defined by the parameters.
     * @link https://url.spec.whatwg.org/#dom-URL-URL URL Standard
     * @link https://developer.mozilla.org/docs/Web/API/URL/URL URL() - Web API Interfaces | MDN
     * @param string $url Is a DOMString representing an absolute or relative URL.
     *      If $url is a relative URL, $base which is present, will be used as the base URL.
     *      If $url is an absolute URL, $base are ignored.
     * @param string $base Is a DOMString representing the base URL to use in case $url is a relative URL.
     *      If not specified, and no $base is passed in parameters, it default to 'about:blank'.
     * @throws TypeError If the given base URL or the resulting URL are not valid URLs, a TypeError is thrown.
     */
    public function __construct($url, $base = null) {
        $parsedBase = null;
        if (!is_null($base)) {
            $parsedBase = lib\URL::parseBasicURL(TypeHinter::to('USVString', $base, 1));
            if (!$parsedBase) {
                throw new TypeError(sprintf('<%s> is not a valid URL', $base));
            }
        }
        $this->url = lib\URL::parseBasicURL(TypeHinter::to('USVString', $url, 0), $parsedBase);
        if ($this->url === false) {
            throw new TypeError(sprintf('<%s> is not a valid URL', $url));
        }
        $this->queryObject = Closure::bind(function ($query) {
                    return URLSearchParams::createNewURLSearchParamsObject(null, $query);
                }, $this, 'NGSOFT\URL\URLSearchParams')->__invoke($this->url->query);
        Closure::bind(function ($queryObject) {
            $queryObject->urlObject = $this;
        }, $this, $this->queryObject)->__invoke($this->queryObject);
    }

    /**
     * Converts domain name to IDNA ASCII form.
     * @deprecated 5.0.0 URL::domainToASCII() has been removed from the URL Standard specification.
     * @see HostProcessing::domainToASCII()
     * @link https://github.com/whatwg/url/commit/2bd0f59b98024921ab90e628b7a526cca5abcb5f
     *      Remove URL.domainToASCII and domainToUnicode · whatwg/url@2bd0f59y
     * @param string $domain
     * @return string Returns an empty string if $domain is an IPv6 address or an invalid domain.
     */
    public static function domainToASCII($domain) {
        $asciiDomain = HostProcessing::parseHost(TypeHinter::to('USVString', $domain), true);
        return is_string($asciiDomain) ? $asciiDomain : '';
    }

    /**
     * Converts domain name from IDNA ASCII to Unicode.
     * @deprecated 5.0.0 URL::domainToUnicode() has been removed from the URL Standard specification.
     * @see HostProcessing::domainToUnicode()
     * @link https://github.com/whatwg/url/commit/2bd0f59b98024921ab90e628b7a526cca5abcb5f
     *      Remove URL.domainToASCII and domainToUnicode · whatwg/url@2bd0f59y
     * @param string $domain
     * @return string Returns an empty string if $domain is an IPv6 address or an invalid domain.
     */
    public static function domainToUnicode($domain) {
        $asciiDomain = HostProcessing::parseHost(TypeHinter::to('USVString', $domain), true);
        return is_string($asciiDomain) ? HostProcessing::domainToUnicode($asciiDomain) : '';
    }

    /**
     * @param string $name
     * @param string $value
     * @throws TypeError
     */
    public function __set($name, $value) {
        if (in_array(
                        $name,
                        ['href', 'protocol', 'username', 'password', 'host', 'hostname', 'port', 'pathname', 'search', 'hash']
                )) {
            $input = TypeHinter::to('USVString', $value);
        }

        switch ($name) {
            case 'href':
                $parsedURL = lib\URL::parseBasicURL($input);
                if (!$parsedURL) {
                    throw new TypeError(sprintf('<%s> is not a valid URL', $input));
                }
                $this->url = $parsedURL;
                Closure::bind(function ($list, $queryObject) {
                            array_splice($queryObject->list, 0, count($queryObject->list), $list);
                        }, null, $this->queryObject)
                        ->__invoke(URLencoding::parseURLencodedString($this->url->query), $this->queryObject);
                break;

            case 'protocol':
                lib\URL::parseBasicURL($input . ':', null, null, [
                    'url' => $this->url,
                    'state override' => 'scheme start state'
                ]);
                break;

            case 'username':
                if (!$this->url->cannotHaveUsernamePasswordPort()) {
                    $this->url->setUsername($input);
                }
                break;

            case 'password':
                if (!$this->url->cannotHaveUsernamePasswordPort()) {
                    $this->url->setPassword($input);
                }
                break;

            case 'host':
                if (!$this->url->cannotBeABaseURLFlag) {
                    lib\URL::parseBasicURL($input, null, null, [
                        'url' => $this->url,
                        'state override' => 'host state'
                    ]);
                }
                break;

            case 'hostname':
                if (!$this->url->cannotBeABaseURLFlag) {
                    lib\URL::parseBasicURL($input, null, null, [
                        'url' => $this->url,
                        'state override' => 'hostname state'
                    ]);
                }
                break;

            case 'port':
                if (!$this->url->cannotHaveUsernamePasswordPort()) {
                    if ($input === '') {
                        $this->url->port = null;
                    } else {
                        lib\URL::parseBasicURL($input, null, null, [
                            'url' => $this->url,
                            'state override' => 'port state'
                        ]);
                    }
                }
                break;

            case 'pathname':
                if (!$this->url->cannotBeABaseURLFlag) {
                    $this->url->path = [];
                    lib\URL::parseBasicURL($input, null, null, [
                        'url' => $this->url,
                        'state override' => 'path start state'
                    ]);
                }
                break;

            case 'search':
                if ($input === '') {
                    $this->url->query = null;
                    $list = [];
                } else {
                    $query = $input[0] === '?' ? substr($input, 1) : $input;
                    $this->url->query = '';
                    lib\URL::parseBasicURL($query, null, null, [
                        'url' => $this->url,
                        'state override' => 'query state'
                    ]);
                    $list = URLencoding::parseURLencodedString($query);
                }
                Closure::bind(function ($list, $queryObject) {
                    array_splice($queryObject->list, 0, count($queryObject->list), $list);
                }, null, $this->queryObject)->__invoke($list, $this->queryObject);
                break;

            case 'hash':
                if ($input === '') {
                    $this->url->fragment = null;
                } else {
                    $fragment = $input[0] === '#' ? substr($input, 1) : $input;
                    $this->url->fragment = '';
                    lib\URL::parseBasicURL($fragment, null, null, [
                        'url' => $this->url,
                        'state override' => 'fragment state'
                    ]);
                }
                break;

            case 'origin':
            case 'searchParams':
                TypeHinter::throwReadonlyException();
                break;

            default:
                TypeHinter::triggerVisibilityErrorOrDefineProperty();
        }
    }

    /**
     * @param string $name
     * @return string|URLSearchParams
     */
    public function __get($name) {
        switch ($name) {
            case 'href':
                $value = $this->url->serializeURL();
                break;

            case 'origin':
                $value = self::serialiseOrigin($this->url->getOrigin());
                break;

            case 'protocol':
                $value = $this->url->scheme . ':';
                break;

            case 'username':
                $value = $this->url->username;
                break;

            case 'password':
                $value = $this->url->password;
                break;

            case 'host':
                $value = is_null($this->url->host) ? '' : HostProcessing::serializeHost($this->url->host)
                        . (is_null($this->url->port) ? '' : ':' . $this->url->port);
                break;

            case 'hostname':
                $value = is_null($this->url->host) ? '' : HostProcessing::serializeHost($this->url->host);
                break;

            case 'port':
                $value = is_null($this->url->port) ? '' : (string) $this->url->port;
                break;

            case 'pathname':
                if ($this->url->cannotBeABaseURLFlag) {
                    $value = $this->url->path[0];
                } elseif (!$this->url->path) {
                    $value = '';
                } else {
                    $value = '/' . implode('/', $this->url->path);
                }
                break;

            case 'search':
                $value = is_null($this->url->query) || $this->url->query === '' ? '' : '?' . $this->url->query;
                break;

            case 'searchParams':
                $value = $this->queryObject;
                break;

            case 'hash':
                $value = is_null($this->url->fragment) || $this->url->fragment === '' ? '' : '#' . $this->url->fragment;
                break;

            default:
                TypeHinter::triggerVisibilityErrorOrUndefinedNotice();
                $value = null;
        }

        return $value;
    }

    /**
     * The serialization of an origin.
     * @link https://html.spec.whatwg.org/multipage/browsers.html#ascii-serialisation-of-an-origin HTML Standard
     * @param string[]|string $origin
     * @return string
     */
    private static function serialiseOrigin($origin) {
        if (!is_array($origin)) {
            $result = 'null';
        } else {
            $result = $origin[0] . '://' . HostProcessing::serializeHost($origin[1]);
            if (isset(lib\URL::$specialSchemes[$origin[0]]) && $origin[2] !== lib\URL::$specialSchemes[$origin[0]]) {
                $result .= ':' . $origin[2];
            }
        }
        return $result;
    }

    /**
     * Returns a USVString containing the whole URL. It is a synonym for URL::$href.
     * @link https://url.spec.whatwg.org/#URL-stringification-behavior URL Standard
     * @return string USVString.
     */
    public function __toString() {
        return $this->__get('href');
    }

    /**
     * Returns the href property value.
     *
     * The URL Standard defines the URL::toJSON() method as to return data which should be serialized to JSON
     * for the JSON::stringify() method on ECMAScript.
     * However, in PHP, a toJSON() method is often defined as to return JSON string,
     * for example the Zend\Json\Json::encode() and the lluminate\Contracts\Support\Jsonable::toJson() method.
     * @link https://url.spec.whatwg.org/#dom-url-tojson URL Standard
     * @return string USVString.
     */
    public function jsonSerialize() {
        return $this->__get('href');
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name) {
        return in_array($name, ['href', 'origin', 'protocol', 'username', 'password', 'host', 'hostname', 'port', 'pathname', 'search', 'searchParams', 'hash']);
    }

    ////////////////////////////   PSR-7   ////////////////////////////

    /** {@inheritdoc} */
    public function getScheme() {
        return strtolower(preg_replace('/\:$/', "", $this->protocol ?? ""));
    }

    /** {@inheritdoc} */
    public function withScheme($scheme) {
        if (!is_string($scheme)) throw new InvalidArgumentException('Scheme must be a string');
        $scheme = strtolower($scheme);
        if ($scheme === $this->getScheme()) return $this;
        $c = clone $this;
        $c->protocol = $scheme;
        return $c;
    }

    /** {@inheritdoc} */
    public function getHost() {
        return strtolower($this->hostname ?? "");
    }

    /** {@inheritdoc} */
    public function getPath() {
        return $this->pathname;
    }

    /** {@inheritdoc} */
    public function getPort() {
        return $this->port ? (int) $this->port : null;
    }

    /** {@inheritdoc} */
    public function getQuery() {
        return preg_replace('/^\?/', "", $this->search);
    }

    /** {@inheritdoc} */
    public function getUserInfo() {
        if (empty($this->username)) return "";
        $user = $this->username;
        if (!empty($this->password)) $user .= ":" . $this->password;
        return $user;
    }

    /** {@inheritdoc} */
    public function getAuthority() {
        return (($auth = $this->getUserInfo()) ? "$auth@" : "") .
                $this->getHost() .
                (($port = $this->getPort()) ? ":$port" : "");
    }

    /** {@inheritdoc} */
    public function getFragment() {
        return $this->url->fragment ?? "";
    }

    /** {@inheritdoc} */
    public function withFragment($fragment) {
        assert(is_string($fragment));
        $c = clone $this;
        $c->hash = $fragment;
        return $c;
    }

    /** {@inheritdoc} */
    public function withHost($host) {
        if (!is_string($host)) throw new InvalidArgumentException("Invalid Host");
        $c = clone $this;
        $c->hostname = $host;
        return $c;
    }

    /** {@inheritdoc} */
    public function withPath($path) {
        if (!is_string($path)) throw new InvalidArgumentException("Invalid Host");
        $c = clone $this;
        $c->pathname = $path;
        return $c;
    }

    /** {@inheritdoc} */
    public function withPort($port) {
        if (
                !is_null($port)
                and (
                !is_int($port) or (
                (0 > $port or 65535 < $port))
                )
        ) {
            throw new InvalidArgumentException("Invalid Port");
        }
        $c = clone $this;
        $c->port = $port ?? "";
        return $c;
    }

    /** {@inheritdoc} */
    public function withQuery($query) {
        if (!is_string($query)) throw new InvalidArgumentException("Invalid Query");
        $c = clone $this;
        $c->search = $query;
        return $c;
    }

    /** {@inheritdoc} */
    public function withUserInfo($user, $password = null) {
        assert(is_string($user) and ( is_string($password) or is_null($password)));
        $c = clone $this;
        $c->username = $user;
        $c->password = $password;
        return $c;
    }

    /** {@inheritdoc} */
    public function __clone() {
        $this->url = clone $this->url;
        $this->queryObject = clone $this->queryObject;
    }

}
