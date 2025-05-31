import React, { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import './Main_page.css'
import Background from '../../components/background_gradient'
import video from '../../assets/video/video1.mp4'
import Header from '../../components/MainHeader/header'
import api from '../../api/axios'

function Main_page() {
  const navigate = useNavigate();
  const [profile, setProfile] = useState(null);

  useEffect(() => {
    const fetchProfile = async () => {
      try {
        await api.get('/sanctum/csrf-cookie');
        const res = await api.get('/api/profile');
        setProfile(res.data);
      } catch (error) {
        console.error('Error fetching profile:', error);
        alert('401 error. Check cookies and login session.');
      }
    };

    fetchProfile();
  }, []);

  if (!profile) return <p>Loading...</p>;

  const handleClickSpoonacular = () => {
    navigate('/Spoonacular');
  };

  const handleClickFitbit = () => {
    navigate('/Fitbit');
  };

  const handleClickGooglePage = () => {
    navigate('/GoogleCalendar');
  };

  const handleClickOpenWeather = () => {
    navigate('/OpenWeather');
  };

  const handleClickSpotify = () => {
    navigate('/Spotify');
  };

  return (
    <main>
      <Background />
      <Header />
      <div className='user-profile'>
        <h2>Welcome, {profile.name}</h2>
        <p>Email: {profile.email}</p>
      </div>
      <section className='main-page-content'>
        <div id='#Home' className='introduction-section'>
          <article>
          <h2 className='introduce-sync-h'>Introducing <br />USTP Sync</h2>
            <p>Sync is a web-based application designed to monitor and promote 
              the overall health and wellness of students. It provides a centralized 
              platform where students can track their physical, mental, and 
              emotional well-being, encouraging a healthier and more balanced campus life.</p>
          </article>
          <video autoPlay muted loop className="introduction-video">
            <source src={video} type='video/mp4' />
            Your browser does not support the video tag.
          </video>
        </div>
        <div className='why-sync-section'>
          <article>
            <h2 className='why-sync-h'>Why USTP <br />Sync?</h2>
            <p>Students need more than just academic support.</p>
            <article className='p1'>
              <p>College life is stressful — with deadlines, responsibilities, and personal struggles piling up, wellness often takes a back seat.</p>
            </article>
          </article>
          <section className='card-container'>
            <section className='topcard-container'>
              <div className='topcard'>Helps track physical, mental, and emotional well-being</div>
              <div className='topcard'>Encourages healthy habits and routines</div>
              <div className='topcard'>Offers a centralized space for self-care and balance</div>
            </section>
            <section className='botcard-container'>
              <div className='botcard'>Promotes a proactive, supportive student culture</div>
              <div className='botcard'>=========================</div>
            </section>
          </section>
        </div>
        <div className='services-api-section'>
          <section className='services-container'>
            <h2 className='services-api'>Services/API</h2>
            <section className='api-content'>
              <div onClick={handleClickFitbit} className='services-card'>Fitbit</div>
              <div onClick={handleClickGooglePage} className='services-card'>Google Calendar</div>
              <div onClick={handleClickOpenWeather} className='services-card'>OpenWeatherMap</div>
              <div onClick={handleClickSpoonacular} className='services-card'>Spoonacular</div>
              <div onClick={handleClickSpotify} className='services-card'>Spotify</div>
            </section>
          </section>
        </div>
      </section>
    </main>
  )
}

export default Main_page
