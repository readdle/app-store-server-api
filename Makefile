.PHONY: shell

shell:
	docker run --rm -it -w /app/examples -v $(shell pwd):/app php:8.4.11-cli bash
