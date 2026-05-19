# PHP OpenTelemetry Demo for SUSE Observability

This repository contains a minimal PHP application instrumented with OpenTelemetry and exporting traces to an OpenTelemetry Collector for visualization in SUSE Observability.

The application continuously:
- Sends one successful HTTP request
- Sends one failed HTTP request
- Generates traces every 5 seconds

---

## Repository Structure

```text
.
├── HelloWorld.php
├── composer.json
├── Dockerfile.hello
├── .dockerignore
└── deployment.yaml
```

## Build Docker Image

```bash
docker build -t php-hello-demo -f Dockerfile.hello .
```

## Push Image to Docker Hub
```bash
docker tag php-hello-demo:latest USERNAME/php-hello-demo:latest
docker push USERNAME/php-hello-demo:latest
```
## Deploy to Kubernetes
```bash
kubectl apply -f deployment.yaml
```
Expected output:
```
Hello OpenTelemetry from PHP Demo!
HTTP https://www.google.com OK
HTTP http://localhost:12345/fail failed
```
## OpenTelemetry Configuration
Collector endpoint:
```bash
http://otel-collector-collector.open-telemetry.svc.cluster.local:4318/v1/traces
```
## Expected traces in SUSE Observability.
<img width="2087" height="1051" alt="image" src="https://github.com/user-attachments/assets/a44f5a84-f198-41a1-9c34-50437bcde4c3" />

