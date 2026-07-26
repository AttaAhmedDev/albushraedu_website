import { useCallback, useEffect, useState } from 'react';
import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { api } from '../api/client';
import './Admin.css';

const WS_TYPES = [
  { key: 'english', label: 'English Worksheets' },
  { key: 'math', label: 'Math Worksheets' },
  { key: 'sight', label: 'Sight Worksheets' },
  { key: 'word', label: 'Word Families Worksheets' },
];

const PRES_TYPES = [
  { key: 'english', label: 'English Presentations' },
  { key: 'math', label: 'Math Presentations' },
  { key: 'sight', label: 'Sight Presentations' },
  { key: 'word', label: 'Word Families Presentations' },
];

const LETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');

export default function Admin() {
  const { user, loading, isAdmin, refresh } = useAuth();
  const [tab, setTab] = useState('worksheets');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');

  if (loading) return <p className="empty">Loading…</p>;
  if (!isAdmin) return <Navigate to="/login" replace />;

  return (
    <div className="admin">
      <aside className="admin-sidebar">
        <h2>🛠 Dashboard</h2>
        <p className="admin-user">Hi, {user?.name}</p>
        {[
          ['worksheets', '📄 Worksheets'],
          ['presentations', '🎭 Presentations'],
          ['games', '🎮 Games'],
          ['flashcards', '🃏 Flashcards'],
          ['footer', '📎 Footer'],
          ['profile', '👤 Profile'],
        ].map(([key, label]) => (
          <button
            key={key}
            type="button"
            className={tab === key ? 'active' : ''}
            onClick={() => { setTab(key); setMessage(''); setError(''); }}
          >
            {label}
          </button>
        ))}
      </aside>
      <section className="admin-main">
        {message && <div className="success-msg">{message}</div>}
        {error && <div className="error-msg">{error}</div>}
        {tab === 'worksheets' && <FilesAdmin kind="worksheets" types={WS_TYPES} setMessage={setMessage} setError={setError} />}
        {tab === 'presentations' && <FilesAdmin kind="presentations" types={PRES_TYPES} accept=".ppt,.pptx" setMessage={setMessage} setError={setError} />}
        {tab === 'games' && <GamesAdmin setMessage={setMessage} setError={setError} />}
        {tab === 'flashcards' && <FlashcardsAdmin setMessage={setMessage} setError={setError} />}
        {tab === 'footer' && <FooterAdmin setMessage={setMessage} setError={setError} />}
        {tab === 'profile' && <ProfileAdmin setMessage={setMessage} setError={setError} onSaved={refresh} />}
      </section>
    </div>
  );
}

function FilesAdmin({ kind, types, accept, setMessage, setError }) {
  const [type, setType] = useState(types[0].key);
  const [items, setItems] = useState([]);
  const [title, setTitle] = useState('');
  const [file, setFile] = useState(null);

  const load = useCallback(() => {
    api(`${kind}/${type}`)
      .then((data) => setItems(data.items || []))
      .catch((err) => setError(err.message));
  }, [kind, type, setError]);

  useEffect(() => { load(); }, [load]);

  const upload = async (e) => {
    e.preventDefault();
    setError('');
    setMessage('');
    if (!title || !file) {
      setError('Title and file required');
      return;
    }
    const fd = new FormData();
    fd.append('title', title);
    fd.append('file', file);
    try {
      await api(`${kind}/${type}`, { method: 'POST', formData: fd });
      setTitle('');
      setFile(null);
      setMessage('Uploaded successfully');
      load();
    } catch (err) {
      setError(err.message);
    }
  };

  const remove = async (id) => {
    if (!window.confirm('Delete this file?')) return;
    try {
      await api(`${kind}/${type}/${id}`, { method: 'DELETE' });
      setMessage('Deleted');
      load();
    } catch (err) {
      setError(err.message);
    }
  };

  return (
    <div>
      <h1>{kind === 'worksheets' ? 'Worksheets' : 'Presentations'}</h1>
      <div className="type-tabs">
        {types.map((t) => (
          <button key={t.key} type="button" className={type === t.key ? 'active' : ''} onClick={() => setType(t.key)}>
            {t.label}
          </button>
        ))}
      </div>
      <form className="admin-form" onSubmit={upload}>
        <input placeholder="Title" value={title} onChange={(e) => setTitle(e.target.value)} />
        <input type="file" accept={accept || '*'} onChange={(e) => setFile(e.target.files?.[0] || null)} />
        <button className="btn btn-primary" type="submit">Upload</button>
      </form>
      <div className="admin-list">
        {items.map((item) => (
          <div key={item.id} className="admin-list-item">
            <span>{item.title}</span>
            <button type="button" className="btn btn-danger" onClick={() => remove(item.id)}>Delete</button>
          </div>
        ))}
      </div>
    </div>
  );
}

function GamesAdmin({ setMessage, setError }) {
  const [subject, setSubject] = useState('english');
  const [items, setItems] = useState([]);
  const [name, setName] = useState('');
  const [link, setLink] = useState('');

  const load = useCallback(() => {
    api(`games/${subject}`)
      .then((data) => setItems(data.items || []))
      .catch((err) => setError(err.message));
  }, [subject, setError]);

  useEffect(() => { load(); }, [load]);

  const save = async (e) => {
    e.preventDefault();
    try {
      await api(`games/${subject}`, { method: 'POST', body: { name, link } });
      setName('');
      setLink('');
      setMessage('Game saved');
      load();
    } catch (err) {
      setError(err.message);
    }
  };

  const remove = async (id) => {
    if (!window.confirm('Delete this game?')) return;
    try {
      await api(`games/${subject}/${id}`, { method: 'DELETE' });
      setMessage('Deleted');
      load();
    } catch (err) {
      setError(err.message);
    }
  };

  return (
    <div>
      <h1>Games</h1>
      <div className="type-tabs">
        <button type="button" className={subject === 'english' ? 'active' : ''} onClick={() => setSubject('english')}>English</button>
        <button type="button" className={subject === 'math' ? 'active' : ''} onClick={() => setSubject('math')}>Math</button>
      </div>
      <form className="admin-form" onSubmit={save}>
        <input placeholder="Game name" value={name} onChange={(e) => setName(e.target.value)} />
        <input placeholder="Game link" value={link} onChange={(e) => setLink(e.target.value)} />
        <button className="btn btn-primary" type="submit">Save</button>
      </form>
      <div className="admin-list">
        {items.map((item) => (
          <div key={item.id} className="admin-list-item">
            <span>{item.name} — {item.link}</span>
            <button type="button" className="btn btn-danger" onClick={() => remove(item.id)}>Delete</button>
          </div>
        ))}
      </div>
    </div>
  );
}

function FlashcardsAdmin({ setMessage, setError }) {
  const [kind, setKind] = useState('letters');
  const [items, setItems] = useState([]);
  const [title, setTitle] = useState('');
  const [key, setKey] = useState('A');
  const [file, setFile] = useState(null);

  const load = useCallback(() => {
    api(`flashcards/${kind}`)
      .then((data) => setItems(data.items || []))
      .catch((err) => setError(err.message));
  }, [kind, setError]);

  useEffect(() => {
    setKey(kind === 'letters' ? 'A' : '1');
    load();
  }, [kind, load]);

  const upload = async (e) => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('title', title);
    fd.append('file', file);
    if (kind === 'letters') fd.append('letter', key);
    else fd.append('number', key);
    try {
      await api(`flashcards/${kind}`, { method: 'POST', formData: fd });
      setTitle('');
      setFile(null);
      setMessage('Flashcard uploaded');
      load();
    } catch (err) {
      setError(err.message);
    }
  };

  const remove = async (id) => {
    if (!window.confirm('Delete?')) return;
    try {
      await api(`flashcards/${kind}/${id}`, { method: 'DELETE' });
      setMessage('Deleted');
      load();
    } catch (err) {
      setError(err.message);
    }
  };

  return (
    <div>
      <h1>Flashcards</h1>
      <div className="type-tabs">
        <button type="button" className={kind === 'letters' ? 'active' : ''} onClick={() => setKind('letters')}>Letters</button>
        <button type="button" className={kind === 'numbers' ? 'active' : ''} onClick={() => setKind('numbers')}>Numbers</button>
      </div>
      <form className="admin-form" onSubmit={upload}>
        <input placeholder="Title" value={title} onChange={(e) => setTitle(e.target.value)} />
        {kind === 'letters' ? (
          <select value={key} onChange={(e) => setKey(e.target.value)}>
            {LETTERS.map((l) => <option key={l} value={l}>{l}</option>)}
          </select>
        ) : (
          <input type="number" min="1" max="100" value={key} onChange={(e) => setKey(e.target.value)} />
        )}
        <input type="file" accept=".pdf" onChange={(e) => setFile(e.target.files?.[0] || null)} />
        <button className="btn btn-primary" type="submit">Upload</button>
      </form>
      <div className="admin-list">
        {items.map((item) => (
          <div key={item.id} className="admin-list-item">
            <span>
              {item.file_name}
              {item.letter ? ` (${item.letter})` : ''}
              {item.number_file != null ? ` (#${item.number_file})` : ''}
            </span>
            <button type="button" className="btn btn-danger" onClick={() => remove(item.id)}>Delete</button>
          </div>
        ))}
      </div>
    </div>
  );
}

function FooterAdmin({ setMessage, setError }) {
  const [form, setForm] = useState({ instagram: '', email: '', phone: '' });

  useEffect(() => {
    api('settings/footer')
      .then((data) => setForm({
        instagram: data.instagram || '',
        email: data.email || '',
        phone: data.phone || '',
      }))
      .catch((err) => setError(err.message));
  }, [setError]);

  const save = async (e) => {
    e.preventDefault();
    try {
      await api('settings/footer', { method: 'PUT', body: form });
      setMessage('Footer updated');
    } catch (err) {
      setError(err.message);
    }
  };

  return (
    <div>
      <h1>Footer Settings</h1>
      <form className="admin-form stacked" onSubmit={save}>
        <input placeholder="Instagram URL" value={form.instagram} onChange={(e) => setForm({ ...form, instagram: e.target.value })} />
        <input placeholder="Email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} />
        <input placeholder="Phone" value={form.phone} onChange={(e) => setForm({ ...form, phone: e.target.value })} />
        <button className="btn btn-primary" type="submit">Save</button>
      </form>
    </div>
  );
}

function ProfileAdmin({ setMessage, setError, onSaved }) {
  const { user } = useAuth();
  const [form, setForm] = useState({ newEmail: user?.email || '', currentPassword: '', newPassword: '' });

  const save = async (e) => {
    e.preventDefault();
    try {
      await api('admin/profile', { method: 'PUT', body: form });
      setMessage('Profile updated');
      setForm((f) => ({ ...f, currentPassword: '', newPassword: '' }));
      onSaved?.();
    } catch (err) {
      setError(err.message);
    }
  };

  return (
    <div>
      <h1>Admin Profile</h1>
      <form className="admin-form stacked" onSubmit={save}>
        <input type="email" placeholder="New email" value={form.newEmail} onChange={(e) => setForm({ ...form, newEmail: e.target.value })} />
        <input type="password" placeholder="Current password" value={form.currentPassword} onChange={(e) => setForm({ ...form, currentPassword: e.target.value })} />
        <input type="password" placeholder="New password" value={form.newPassword} onChange={(e) => setForm({ ...form, newPassword: e.target.value })} />
        <button className="btn btn-primary" type="submit">Update</button>
      </form>
    </div>
  );
}
