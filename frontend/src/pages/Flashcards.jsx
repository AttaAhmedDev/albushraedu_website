import { useParams } from 'react-router-dom';
import { fileUrl } from '../api/client';
import './Flashcards.css';

const LETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
const NUMBERS = Array.from({ length: 20 }, (_, i) => i + 1);

export default function Flashcards() {
  const { kind } = useParams();
  const isLetters = kind === 'letters';

  const download = (key) => {
    window.location.href = fileUrl(`flashcards/${kind}/${key}/download`);
  };

  return (
    <div className={`flashcards-page ${isLetters ? 'letters-bg' : 'numbers-bg'}`}>
      <div className="flashcards-box">
        <h1>{isLetters ? 'Magic Letters' : 'Magic Numbers'}</h1>
        <p>Tap to download a flashcard PDF</p>
        <div className="flash-grid">
          {(isLetters ? LETTERS : NUMBERS).map((item) => (
            <button
              key={item}
              type="button"
              className="flash-item"
              onClick={() => download(item)}
            >
              {item}
            </button>
          ))}
        </div>
      </div>
    </div>
  );
}
