import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { api, fileUrl } from '../api/client';

const TITLES = {
  english: 'English Worksheets',
  math: 'Math Worksheets',
  sight: 'Sight Words Worksheets',
  word: 'Word Families Worksheets',
};

export default function Worksheets() {
  const { type } = useParams();
  const [items, setItems] = useState([]);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    setLoading(true);
    setError('');
    api(`worksheets/${type}`)
      .then((data) => setItems(data.items || []))
      .catch((err) => setError(err.message))
      .finally(() => setLoading(false));
  }, [type]);

  return (
    <div className="page">
      <h1 className="page-title">📄 {TITLES[type] || 'Worksheets'}</h1>
      <p className="page-sub">Download or view fun practice sheets</p>
      {error && <div className="error-msg">{error}</div>}
      {loading ? (
        <p className="empty">Loading…</p>
      ) : items.length === 0 ? (
        <p className="empty">No worksheets yet.</p>
      ) : (
        <div className="grid-cards">
          {items.map((item) => (
            <div className="item-card" key={item.id}>
              <h3>{item.title}</h3>
              <div className="btn-row">
                <a className="btn btn-primary" href={fileUrl(`worksheets/${type}/${item.id}/view`)} target="_blank" rel="noreferrer">View</a>
                <a className="btn btn-secondary" href={fileUrl(`worksheets/${type}/${item.id}/download`)}>Download</a>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
