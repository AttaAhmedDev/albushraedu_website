import { Link } from 'react-router-dom';
import { publicAsset } from '../api/client';
import './Home.css';

export default function Home() {
  return (
    <div className="home">
      <div className="letters">
        <div className="letters-wrapper">
          {'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('').map((l) => (
            <span key={l}>{l}</span>
          ))}
        </div>
      </div>

      <section className="hero">
        <div className="hero-left">
          <div className="img-box">
            <img
              src={publicAsset('images/Gemini_Generated_Image_fc5puofc5puofc5p.png')}
              alt="Ms. Albushra"
              onError={(e) => {
                e.currentTarget.src = 'https://via.placeholder.com/400x500/FDF2E9/E63946?text=Teacher';
              }}
            />
            <div className="badge">🍎</div>
          </div>
        </div>
        <div className="hero-right">
          <h5>WELCOME TO</h5>
          <h1>Ms. Albushra&apos;s <br /><span>World!</span></h1>
          <p>
            Hello, wonderful families! I&apos;m Ms. Albushra Ayesh, a passionate Kindergarten teacher
            dedicated to nurturing every child&apos;s love of learning. I believe that the early years
            are the most important — and I&apos;m here to make them magical, meaningful, and fun.
          </p>
          <div className="tags">
            <span>B.A. English Literature 📘</span>
            <span>Certified Trainer (TOT) 🏅</span>
            <span>M.A. Educational Leadership 🎓</span>
          </div>
        </div>
      </section>

      <section id="about" className="teacher-section">
        <div className="teacher-header">
          <span className="subtitle">🌟 MEET YOUR TEACHER 👩‍🏫</span>
          <h2>Experienced, creative, and deeply committed to every child&apos;s growth.</h2>
        </div>
        <div className="teacher-grid">
          <div className="teacher-card">
            <div className="card-icon">📚</div>
            <h3>Highly Qualified</h3>
            <p>Bachelor&apos;s in English Literature, Master&apos;s in Educational Management and Leadership, and certified Trainer of Trainers (TOT).</p>
          </div>
          <div className="teacher-card">
            <div className="card-icon">🌍</div>
            <h3>Bilingual Learning</h3>
            <p>Supports children in developing strong skills in both English and Arabic.</p>
          </div>
          <div className="teacher-card">
            <div className="card-icon">💡</div>
            <h3>Creative Teaching</h3>
            <p>Creative activities, worksheets, videos, and play-based learning make every lesson an adventure.</p>
          </div>
        </div>
      </section>

      <section className="learning-session">
        <h2 className="session-title">📚 What Do We Learn?</h2>
        <p className="session-description">✨ A rich, well-rounded curriculum designed for young, curious minds! ✨</p>
        <div className="cards-row">
          {['🔤 Letters & Words', '🔢 Numbers & Math', '🗣️ Language Skills', '👁️ Sight Words',
            '🧠 Cognitive Skills', '📖 Story Time', '👨‍👩‍👧‍👦 Word families', '🎨 Arts & Crafts'].map((item) => (
            <div className="learning-card" key={item}>
              <span className="card-icon-lg">{item.split(' ')[0]}</span>
              <span className="card-title">{item.slice(item.indexOf(' ') + 1)}</span>
            </div>
          ))}
        </div>
      </section>

      <section className="kids-section">
        <div className="card card1">
          <div className="icon">📄</div>
          <h2>Worksheets</h2>
          <p>Carefully designed worksheets to build language, literacy, and number skills step by step.</p>
          <Link to="/worksheets/english" className="card-link">Let&apos;s Go →</Link>
        </div>
        <div className="card card3">
          <div className="icon">🎮</div>
          <h2>Activities & Games</h2>
          <p>Hands-on creative activities and interactive games that bring learning to life!</p>
          <Link to="/games/english" className="card-link">Let&apos;s Go →</Link>
        </div>
      </section>

      <section className="teacher-message-section">
        <div className="teacher-message-card">
          <h2>💌 A Message from Ms. Albushra Ayesh</h2>
          <p>🌸 Dear parents and families, welcome to our classroom corner! This website is a space created with love — where children can explore worksheets and enjoy fun activities that support learning.</p>
          <p>📚 My goal is to nurture each child&apos;s language, social, and cognitive skills in a warm, bilingual environment.</p>
          <p>🌟 Every child is unique, capable, and full of potential. Together, let&apos;s help them shine!</p>
          <div className="teacher-signature">Ms. Albushra Ayesh 🌻</div>
        </div>
      </section>
    </div>
  );
}
