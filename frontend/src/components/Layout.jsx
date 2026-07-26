import { useEffect, useState } from 'react';
import { Link, NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { api } from '../api/client';
import './Layout.css';

export default function Layout({ children }) {
  const { user, logout, isAdmin, loading } = useAuth();
  const [menuOpen, setMenuOpen] = useState(false);
  const [footer, setFooter] = useState(null);
  const [wsOpen, setWsOpen] = useState(false);
  const [presOpen, setPresOpen] = useState(false);
  const [gamesOpen, setGamesOpen] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    api('settings/footer').then(setFooter).catch(() => {});
  }, []);

  const closeAll = () => {
    setMenuOpen(false);
    setWsOpen(false);
    setPresOpen(false);
    setGamesOpen(false);
  };

  const handleLogout = async () => {
    await logout();
    closeAll();
    navigate('/');
  };

  return (
    <div className="app-shell">
      <header className="navbar">
        <Link to="/" className="logo" onClick={closeAll}>
          <span className="star">🌟</span>
          <div>
            <h3>Ms. Albushra&apos;s World</h3>
            <p>KINDERGARTEN CORNER</p>
          </div>
        </Link>
        <button className="hamburger" type="button" aria-label="Menu" onClick={() => setMenuOpen((v) => !v)}>
          <span /><span /><span />
        </button>
        <nav className={menuOpen ? 'open' : ''} id="navMenu">
          <NavLink to="/" end onClick={closeAll}>🏠 Home</NavLink>
          <button type="button" className="nav-btn" onClick={() => { setWsOpen(true); setMenuOpen(false); }}>📄 Worksheets</button>
          <button type="button" className="nav-btn" onClick={() => { setPresOpen(true); setMenuOpen(false); }}>🎭 Fun Presentations</button>
          <a href="#about" onClick={closeAll}>About Me</a>
          <button type="button" className="nav-btn" onClick={() => { setGamesOpen(true); setMenuOpen(false); }}>🎮 Online Games</button>
          <NavLink to="/flashcards/letters" onClick={closeAll}>📘 English Flashcards</NavLink>
          <NavLink to="/flashcards/numbers" onClick={closeAll}>🧮 Math Flashcards</NavLink>
          {!loading && isAdmin && <NavLink to="/admin" onClick={closeAll}>🛠 Dashboard</NavLink>}
          {!loading && user ? (
            <button type="button" className="nav-btn" onClick={handleLogout}>🚪 Logout</button>
          ) : (
            <>
              <NavLink to="/login" onClick={closeAll}>🔐 Login</NavLink>
              <NavLink to="/register" onClick={closeAll}>📝 Register</NavLink>
            </>
          )}
        </nav>
      </header>

      <main>{children}</main>

      <footer className="footer">
        <div className="footer-container">
          <div className="footer-logo">
            <span className="star">🌟</span>
            <div>
              <h4>Albushra&apos;s World</h4>
              <p>❤️ kindergarten adventure</p>
            </div>
          </div>
          <div className="footer-social">
            <a href={footer?.instagram || '#'} target="_blank" rel="noopener noreferrer">
              <i className="fab fa-instagram" />
              <span>{footer?.insta_handle || '@albushra.kids'}</span>
            </a>
            <a href={`mailto:${footer?.email || ''}`}>
              <i className="fas fa-envelope" />
              <span>{footer?.email || 'Albushra.ayesh@gmail.com'}</span>
            </a>
            <a href={`tel:${(footer?.phone || '').replace(/[^0-9+]/g, '')}`}>
              <i className="fas fa-phone-alt" />
              <span>{footer?.phone || '+201002345678'}</span>
            </a>
          </div>
        </div>
        <div className="footer-copyright">
          <p>© {new Date().getFullYear()} Ms. Albushra&apos;s World — where little hands create big dreams ✨</p>
        </div>
      </footer>

      {wsOpen && (
        <Modal title="📚 Choose Worksheets" onClose={() => setWsOpen(false)}>
          <Link className="modal-option" to="/worksheets/word" onClick={closeAll}>Word Families Worksheets</Link>
          <Link className="modal-option" to="/worksheets/sight" onClick={closeAll}>Sight Words Worksheets</Link>
          <Link className="modal-option" to="/worksheets/math" onClick={closeAll}>Math Worksheets</Link>
          <Link className="modal-option" to="/worksheets/english" onClick={closeAll}>English Worksheets</Link>
        </Modal>
      )}
      {presOpen && (
        <Modal title="🎭 Fun Presentations" onClose={() => setPresOpen(false)}>
          <Link className="modal-option" to="/presentations/word" onClick={closeAll}>Word Families</Link>
          <Link className="modal-option" to="/presentations/sight" onClick={closeAll}>Sight Words</Link>
          <Link className="modal-option" to="/presentations/math" onClick={closeAll}>Math</Link>
          <Link className="modal-option" to="/presentations/english" onClick={closeAll}>English</Link>
        </Modal>
      )}
      {gamesOpen && (
        <Modal title="🎮 Choose your game!" onClose={() => setGamesOpen(false)}>
          <Link className="modal-option" to="/games/english" onClick={closeAll}>📖 English Online Games</Link>
          <Link className="modal-option" to="/games/math" onClick={closeAll}>🧮 Math Online Games</Link>
        </Modal>
      )}
    </div>
  );
}

function Modal({ title, onClose, children }) {
  return (
    <div className="app-modal">
      <div className="modal-overlay" onClick={onClose} />
      <div className="modal-container">
        <button type="button" className="modal-close" onClick={onClose}>&times;</button>
        <h2>{title}</h2>
        <div className="modal-options">{children}</div>
      </div>
    </div>
  );
}
