<?php

require 'vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;
use OpenTelemetry\Contrib\Otlp\SpanExporter;

putenv("OTEL_SERVICE_NAME=php-otel");

$transport = (new OtlpHttpTransportFactory())->create(
    'http://otel-collector-collector.open-telemetry.svc.cluster.local:4318/v1/traces',
    'application/x-protobuf'
);

$exporter = new SpanExporter($transport);

$tracerProvider = new TracerProvider(
    new SimpleSpanProcessor($exporter)
);

$tracer = $tracerProvider->getTracer('php-demo');

function sendRequest($url, $tracer)
{
    $span = $tracer->spanBuilder("GET $url")->startSpan();

    try {
        $result = @file_get_contents($url);

        if ($result === false) {
            throw new Exception("Request failed");
        }

        echo "HTTP $url OK\n";

    } catch (Exception $e) {
        echo "HTTP $url failed\n";
        $span->recordException($e);
    } finally {
        $span->end();
    }
}

while (true) {
    echo "Hello OpenTelemetry from PHP Demo!\n";

    sendRequest("https://www.google.com", $tracer);

    sendRequest("http://localhost:12345/fail", $tracer);

    sleep(5);
}
?>
