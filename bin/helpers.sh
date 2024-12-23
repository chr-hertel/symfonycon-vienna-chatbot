#!/bin/sh

start () {
  echo "\033[0;32m"
  echo "  _____ _           _   _           ___          __        _        _"
  echo " / ____| |         | | | |         | \ \        / /       | |      | |"
  echo "| |    | |__   __ _| |_| |__   ___ | |\ \  /\  / /__  _ __| | _____| |__   ___  _ __"
  echo "| |    | '_ \ / _\` | __| '_ \ / _ \| __\ \/  \/ / _ \| '__| |/ / __| '_ \ / _ \| '_ \\"
  echo "| |____| | | | (_| | |_| |_) | (_) | |_ \  /\  / (_) | |  |   <\__ \ | | | (_) | |_) |"
  echo " \_____|_| |_|\__,_|\__|_.__/ \___/ \__| \/  \/ \___/|_|  |_|\_\___/_| |_|\___/| .__/"
  echo "                                                                               | |"
  echo "                                                                               |_|"
  echo "\033[0;34m"
}

yellowline () {
  echo "\033[0;33m"
  echo "===================================================================================="
  echo "\033[0m"
}

headline () {
  yellowline
  echo "                                    \033[1m$1\033[0m"
  yellowline
}

docker_php () {
  docker compose exec app "$@"
}

end () {
  echo "\033[0;32m"
  echo "          _ _      _____ _               _            _____                       _"
  echo "    /\   | | |    / ____| |             | |          |  __ \                     | |"
  echo "   /  \  | | |   | |    | |__   ___  ___| | _____    | |__) |_ _ ___ ___  ___  __| |"
  echo "  / /\ \ | | |   | |    | '_ \ / _ \/ __| |/ / __|   |  ___/ _\` / __/ __|/ _ \/ _\` |"
  echo " / ____ \| | |   | |____| | | |  __/ (__|   <\__ \   | |  | (_| \__ \__ \  __/ (_| |"
  echo "/_/    \_\_|_|    \_____|_| |_|\___|\___|_|\_\___/   |_|   \__,_|___/___/\___|\__,_|"
  echo "\033[0m"
}
