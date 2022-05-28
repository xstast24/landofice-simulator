.PHONY: info docker-build docker-run docker-run-bash run

# use 0.0.0.0, because "localhost" might fail to connect in some cases, see https://stackoverflow.com/a/38570561/7684041
HOST = "0.0.0.0"
PORT = "8069"
PHP_VERSION = "5.6"
DOCKER_IMAGE_NAME = "php:$(PHP_VERSION)-alpine"
CURRENT_DIRECTORY = $(shell pwd)

info:
	@echo
	@echo "Rozjet simulator lokalne lze snadno pomoci:"
	@echo "	<docker-run> spusti simulator v dockeru -> staci nainstalovany docker a vse by melo fungovat."
	@echo "	<run> spusti simulator pomoci lokalniho PHP webserveru. Vyzaduje spravnou verzi php nainstalovanou a funkcni lokalne (primo v pocitaci)"
	@echo "PS: Vsechny zmeny ucinene v repositari by mely byt automaticky promitnute na webu. Neni potreba nikam kopirovat soubory atp."
	@echo "---"
	@echo "Po spusteni je simulator dostupny v prohlizeci na adrese $(HOST):$(PORT)"
	@echo

info-short:
	@echo
	@echo "Simulator bude dostupny v prohlizeci na adrese $(HOST):$(PORT)"
	@echo

docker-build: info-short
	@docker pull $(DOCKER_IMAGE_NAME) && echo "Pulling docker image DONE" || echo "Pulling docker image \"$(DOCKER_IMAGE_NAME)\" FAILED"

docker-run: docker-build
# it = interactively run in console to see php output; rm = delete the container after closed (image stays untouched)
# p = make port from container visible to host; v = mount/link this repo to the container; name = custom name of the container in docker (cosmetic purpose)
# php... this command in launched in the container to run the PHP server
	@docker run --name landofice-simulator -it --rm -p $(PORT):$(PORT) -v $(CURRENT_DIRECTORY):/loi/simulator $(DOCKER_IMAGE_NAME) php -t /loi/simulator -S $(HOST):$(PORT)

docker-run-bash: docker-build
# run the docker with bash console for debug purposes, custom playing with the php server etc.
	@docker run --name landofice-simulator -it --rm --entrypoint sh -p $(PORT):$(PORT) -v $(pwd):/loi/simulator $(DOCKER_IMAGE_NAME)

run: info-short
	@php -t $(CURRENT_DIRECTORY) -S $(HOST):$(PORT)
