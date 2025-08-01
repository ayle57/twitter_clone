import './styles/app.scss';

function importAll(r) {
    r.keys().forEach(r);
}

importAll(require.context('./controllers/', true, /\.jsx$/));
