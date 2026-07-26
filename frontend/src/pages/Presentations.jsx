import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { api, fileUrl } from '../api/client';

const TITLES = {
  english: 'English Presentations',
  math: 'Math Presentations',
  sight: 'Sight Words Presentations',
  word: 'Word Families Presentations',
};

export default function Presentations() {
  const { type } = useParams();
  const [items, setItems] = useState([]);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    setLoading(true);
    api(`presentations/${type}`)
      .then((data) => setItems(data.items || []))
      .catch((err) => setError(err.message))
      .finally(() => setLoading(false));
  }, [type]);

  return (
    <div className="page">
      <h1 className="page-title">🎭 {TITLES[type] || 'Presentations'}</h1>
      <p className="page-sub">Fun presentations to watch and learn</p>
      {error && <div className="error-msg">{error}</div>}
      {loading ? (
        <p className="empty">Loading…</p>
      ) : items.length === 0 ? (
        <p className="empty">No presentations yet.</p>
      ) : (
        <div className="grid-cards">
          {items.map((item) => (
            <div className="item-card" key={item.id}>
              <h3>{item.title}</h3>
              <div className="btn-row">
                <a className="btn btn-primary" href={fileUrl(`presentations/${type}/${item.id}/download`)}>Download</a>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
