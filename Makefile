.PHONY: info docker-build docker-run docker-run-bash run

# use 0.0.0.0, because "localhost" might fail to connect in some cases, see https://stackoverflow.com/a/38570561/7684041
HOST = "0.0.0.0"
PORT = "8069"
# load from config
PHP_VERSION = $(shell grep php_version config.ini --max-count 1 | cut -f 3 -d ' ' | tr -d ' ')
DOCKER_IMAGE_NAME = "php:$(PHP_VERSION)-alpine"

DEV_SIM_HOST="landofice-simulator.4fan.cz"

CURRENT_DIR = $(shell pwd)
DEV_DIR = "$(CURRENT_DIR)/dev"


info:
	@echo
	@echo "<info> Vypise help"
	@echo
	@echo "<release> Vytvori release package a zaroven vse uploadne na developer simulator server ($(DEV_SIM_HOST))"
	@echo
	@echo "<run> Lokalne spusti simulator pomoci nainstalovenaheo lokalniho PHP. Vyzaduje spravnou verzi PHP nainstalovanou a funkcni lokalne v terminalu"
	@echo "<docker-run> Lokalne spusti simulator v dockeru (staci nainstalovany docker a vse by melo fungovat samo)"
	@echo "Note: Po lokalnim spusteni (at s dockerem nebo bez) je simulator dostupny v prohlizeci na adrese $(HOST):$(PORT)"
	@echo "    Zmeny ucinene v repositari by mely byt automaticky promitnute na webu. Neni potreba nikam kopirovat soubory atp."
	@echo

release:
	@$(DEV_DIR)/create_release_package.sh
	@$(DEV_DIR)/ftp_to_dev_sim.sh "$(CURRENT_DIR)"/simulator-release/\*

info-local-address:
	@echo
	@echo "Simulator bude dostupny v prohlizeci na adrese $(HOST):$(PORT)"
	@echo

docker-build:
	@docker pull $(DOCKER_IMAGE_NAME) && echo "Pulling docker image DONE" || echo "Pulling docker image \"$(DOCKER_IMAGE_NAME)\" FAILED"

docker-run: info-local-address docker-build
# it = interactively run in console to see php output; rm = delete the container after closed (image stays untouched)
# p = make port from container visible to host; v = mount/link this repo to the container; name = custom name of the container in docker (cosmetic purpose)
# php... this command in launched in the container to run the PHP server
	@docker run --name landofice-simulator -it --rm -p $(PORT):$(PORT) -v $(CURRENT_DIR):/loi/simulator $(DOCKER_IMAGE_NAME) php -t /loi/simulator -S $(HOST):$(PORT)

docker-run-bash: info-local-address docker-build
# run the docker with bash console for debug purposes, custom playing with the php server etc.
	@docker run --name landofice-simulator -it --rm --entrypoint sh -p $(PORT):$(PORT) -v $(pwd):/loi/simulator $(DOCKER_IMAGE_NAME)

run: info-local-address
	@php -t $(CURRENT_DIR) -S $(HOST):$(PORT)
