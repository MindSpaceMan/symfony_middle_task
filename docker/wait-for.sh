# wait-for.sh (make executable)
#!/bin/sh
host="$1"; shift
port="$1"; shift
until nc -z "$host" "$port"; do
  >&2 echo "Waiting for $host:$port…"
  sleep 1
done
exec "$@"