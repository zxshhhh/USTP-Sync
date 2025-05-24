import React from 'react'
import'./header.css'
import logo from '../../assets/image/USTP-sync-logo.png'

function header() {
  return (
    <div>
        <section className='header-container'>
            <img className='logo-style' src={logo} alt='USTP Sync Logo' draggable="false"/>
            <nav className='navigation-text'>
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </nav>
        </section>
    </div>
  )
}

export default header
