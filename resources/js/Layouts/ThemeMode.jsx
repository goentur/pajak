import { faMoon, faSun } from '@fortawesome/free-regular-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import React, { useState, useEffect } from 'react';

function ThemeMode() {
  const [theme, setTheme] = useState(localStorage.getItem('theme') || 'light');

  useEffect(() => {
    document.documentElement.setAttribute('data-bs-theme', theme);
    localStorage.setItem('theme', theme);
  }, [theme]);

  const toggleTheme = () => {
    const newTheme = theme === 'light' ? 'dark' : 'light';
    setTheme(newTheme);
  };

  const containerStyles = {
    background: theme === 'light' ? '#333' : '#FFF',
    width: '50px',
    height: '25px',
    borderRadius: '12.5px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    padding: '2px',
    cursor: 'pointer',
    boxSizing: 'border-box',
  };

  const buttonStyles = {
    width: '21px',
    height: '21px',
    borderRadius: '50%',
    backgroundColor: theme === 'light' ? '#FFF' : '#333',
    transition: 'transform 0.25s ease-out',
    transform: theme === 'light' ? 'translateX(0)' : 'translateX(24px)',
    display: 'flex',
    alignItems: 'center',
    padding:theme === 'light' ? '2px' : '4px',
  };
  return (
    <div style={containerStyles} onClick={toggleTheme} aria-label={`Switch to ${theme === 'light' ? 'dark' : 'light'} mode`}>
      <div style={buttonStyles}>{theme === 'light' ? <FontAwesomeIcon icon={faSun} className='text-dark' />:<FontAwesomeIcon icon={faMoon} />}</div>
    </div>
  );
}

export default ThemeMode;
