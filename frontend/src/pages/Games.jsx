import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { api } from '../api/client';

export default function Games() {
  const { subject } = useParams();
  const [items, setItems] = useState([]);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    setLoading(true);
    api(`games/${subject}`)
      .then((data) => setItems(data.items || []))
      .catch((err) => setError(err.message))
      .finally(() => setLoading(false));
  }, [subject]);

  return (
    <div className="page">
      <h1 className="page-title">🎮 {subject === 'math' ? 'Math' : 'English'} Online Games</h1>
      <p className="page-sub">Play and learn with fun games</p>
      {error && <div className="error-msg">{error}</div>}
      {loading ? (
        <p className="empty">Loading…</p>
      ) : items.length === 0 ? (
        <p className="empty">No games yet.</p>
      ) : (
        <div className="grid-cards">
          {items.map((item) => (
            <div className="item-card" key={item.id}>
              <h3>{item.name}</h3>
              <div className="btn-row">
                <a className="btn btn-primary" href={item.link} target="_blank" rel="noreferrer">Play →</a>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
