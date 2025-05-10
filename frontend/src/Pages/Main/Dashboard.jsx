import React, { useState, useRef } from 'react';
import './Dashboard.css'; 
import Navbar from '../../Components/Navbar/navbar.jsx';


const videoList = [
  "/assets/ustp-background-videos/ustp-milestones.mp4",
  "/assets/ustp-background-videos/ustp-alubijid.mp4",
  "/assets/ustp-background-videos/ustp-balubal.mp4",
  "/assets/ustp-background-videos/ustp-cdo.mp4",
  "/assets/ustp-background-videos/ustp-claveria.mp4",
  "/assets/ustp-background-videos/ustp-jasaan.mp4",
  "/assets/ustp-background-videos/ustp-oroquita.mp4",
  "/assets/ustp-background-videos/ustp-panaon.mp4",
  "/assets/ustp-background-videos/ustp-villanueva.mp4",
]

function Dashboard() {
  const [ currentIndex, setCurrentIndex ] = React.useState(0);
  const videoRef = useRef(null);

  const handleVideoEnd = () => {
    const nextIndex = (currentIndex + 1) % videoList.length;
    setCurrentIndex(nextIndex);
    if (videoRef.current) {
      videoRef.current.load();
      videoRef.current.play();
    }
  };

  return (
    <section className="dashboard-layout">
      <h2 className='text-sample'>Welcome to our Dashboard</h2>
      <div className='dashboard-container'>
        <video className="background-video" ref={videoRef}  autoPlay loop muted playsInline onEnded={handleVideoEnd}>
          <source src={videoList[currentIndex]} type="video/mp4" />
        </video>
        <div className='background-overlay'></div>
        <Navbar />
      </div>
    </section>
    
  )
}

export default Dashboard