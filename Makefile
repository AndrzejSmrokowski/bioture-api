SHELL := /bin/bash

.PHONY: up down restart ps logs sh \
        cs-fix rector fix \
        cs-check rector-check analyze deptrac \
        test quality

APP = docker compose exec app

# -------------------------
# Docker helpers
# -------------------------
up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose down
	docker compose up -d

ps:
	docker compose ps

logs:
	docker compose logs -f

sh:
	$(APP) bash

# -------------------------
# Single commands (dev / debug)
# -------------------------
cs-fix:
	$(APP) vendor/bin/php-cs-fixer fix --allow-risky=yes

rector:
	$(APP) vendor/bin/rector process

fix: cs-fix rector

cs-check:
	$(APP) vendor/bin/php-cs-fixer fix --dry-run --diff --allow-risky=yes

rector-check:
	$(APP) vendor/bin/rector process --dry-run

analyze:
	$(APP) vendor/bin/phpstan analyse --memory-limit=1G

deptrac:
	$(APP) php -d 'error_reporting=E_ALL&~E_WARNING' vendor/bin/deptrac

test:
	$(APP) vendor/bin/phpunit tests

# -------------------------
# One button: fix → checks → summary (full output)
# -------------------------
quality:
	@bash -lc '\
		set +e; \
		echo "────────────────────────────────────────"; \
		echo "QUALITY (fix → checks → summary)"; \
		echo "────────────────────────────────────────"; \
		echo ""; \
		cs=0; rector=0; phpstan=0; deptrac=0; phpunit=0; \
		echo ">>> CS-Fixer (fix)"; \
		$(MAKE) -s cs-fix; cs=$$?; echo ""; \
		echo ">>> Rector (fix)"; \
		$(MAKE) -s rector; rector=$$?; echo ""; \
		echo ">>> PHPStan (check)"; \
		$(MAKE) -s analyze; phpstan=$$?; echo ""; \
		echo ">>> Deptrac (check)"; \
		$(MAKE) -s deptrac; deptrac=$$?; echo ""; \
		echo ">>> PHPUnit (check)"; \
		$(MAKE) -s test; phpunit=$$?; echo ""; \
		echo "────────────────────────────────────────"; \
		echo "SUMMARY"; \
		echo "────────────────────────────────────────"; \
		[ $$cs -eq 0 ] && echo "CS-Fixer       ✅" || echo "CS-Fixer       ❌ (exit $$cs)"; \
		[ $$rector -eq 0 ] && echo "Rector         ✅" || echo "Rector         ❌ (exit $$rector)"; \
		[ $$phpstan -eq 0 ] && echo "PHPStan        ✅" || echo "PHPStan        ❌ (exit $$phpstan)"; \
		[ $$deptrac -eq 0 ] && echo "Deptrac        ✅" || echo "Deptrac        ❌ (exit $$deptrac)"; \
		[ $$phpunit -eq 0 ] && echo "PHPUnit        ✅" || echo "PHPUnit        ❌ (exit $$phpunit)"; \
		echo "────────────────────────────────────────"; \
		if [ $$cs -eq 0 -a $$rector -eq 0 -a $$phpstan -eq 0 -a $$deptrac -eq 0 -a $$phpunit -eq 0 ]; then \
			echo "✅ QUALITY PASSED"; exit 0; \
		else \
			echo "❌ QUALITY FAILED"; exit 1; \
		fi \
	'
