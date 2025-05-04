https://github.com/pondix/docker-mysql-proxysql/tree/master/conf/mysql/mysql1

Após tudo ficar up, executar script para atachar os container do mysql nos hostgroup do proxy:

```bash
docker container exec proxysql bash -c "sh /usr/local/bin/init.sh"
```

https://dev.to/mattdark/monitoring-mysql-with-prometheus-and-grafana-in-docker-1ij7
https://grafana.com/grafana/dashboards/14031-mysql-dashboard/


Instalar dependências da API
```bash
docker container exec api bash -c "composer update"
```

Execute K6 test
```bash
docker run --rm  --name k6 --network proyx-sql-poc_backend -v "$(pwd)/benchmark:/test" grafana/k6 run /test/loadtest.js
docker run --rm  --name k6 --network proyx-sql-poc_backend -v "$(pwd)/benchmark:/test" grafana/k6 run /test/loadtest-with-proxy.js
```