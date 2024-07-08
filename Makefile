.PHONY: shell

shell:
	docker run --rm -it -w /app -v $(shell pwd):/app php:8.3 bash
