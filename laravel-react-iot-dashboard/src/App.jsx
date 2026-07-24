import { useState, useEffect } from 'react';
import axios from 'axios';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts';

function App() {
  const [chartData, setChartData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [latestData, setLatestData] = useState({ temperature: 0, humidity: 0 });

  const fetchSensorData = () => {
    axios.get('http://127.0.0.1:8000/api/sensor/live-chart')
      .then(response => {
        setChartData(response.data);
        if (response.data.length > 0) {
          // ডাটাবেসের একদম শেষ বা লেটেস্ট অবজেক্টটি উইজেট কার্ডের জন্য ফিল্টার করা
          setLatestData(response.data[response.data.length - 1]);
        }
        setLoading(false);
      })
      .catch(error => {
        console.error("API Connection Error:", error);
        setLoading(false);
      });
  };

  useEffect(() => {
    fetchSensorData();
    const interval = setInterval(fetchSensorData, 3000);
    return () => clearInterval(interval);
  }, []);

  return (
    <div style={{ padding: '30px', fontFamily: 'Arial, sans-serif', backgroundColor: '#f4f6f9', minHeight: '100vh' }}>
      <div style={{ maxWidth: '1000px', margin: '0 auto', backgroundColor: '#fff', padding: '20px', borderRadius: '10px', boxShadow: '0 4px 6px rgba(0,0,0,0.1)' }}>
        <h1 style={{ textAlign: 'center', marginBottom: '5px', color: '#1e293b' }}>IoT Real-Time Weather Dashboard</h1>
        <p style={{ textAlign: 'center', color: '#64748b', marginBottom: '30px' }}>Live Data Feed from Laravel API Server</p>

        {loading ? (
          <h3 style={{ textAlign: 'center', color: '#007bff' }}>Fetching Telemetry from Server...</h3>
        ) : (
          <div>
            {/* 📊 লাইভ অ্যানিমেটেড উইজেট কার্ড সেকশন */}
            <div style={{ display: 'flex', gap: '20px', justifyContent: 'center', marginBottom: '30px' }}>
              {/* তাপমাত্রা কার্ড (লাল থিম) */}
              <div style={{ flex: 1, maxWidth: '300px', padding: '20px', backgroundColor: '#fef2f2', borderLeft: '5px solid #ef4444', borderRadius: '8px', textAlign: 'center', boxShadow: '0 2px 4px rgba(0,0,0,0.05)' }}>
                <h4 style={{ margin: 0, color: '#991b1b', textTransform: 'uppercase', fontSize: '12px' }}>Current Temperature</h4>
                <h2 style={{ margin: '10px 0 0 0', color: '#ef4444', fontSize: '36px' }}>{latestData.temperature}°C</h2>
              </div>
              
              {/* আর্দ্রতা কার্ড (নীল থিম) */}
              <div style={{ flex: 1, maxWidth: '300px', padding: '20px', backgroundColor: '#eff6ff', borderLeft: '5px solid #3b82f6', borderRadius: '8px', textAlign: 'center', boxShadow: '0 2px 4px rgba(0,0,0,0.05)' }}>
                <h4 style={{ margin: 0, color: '#1e40af', textTransform: 'uppercase', fontSize: '12px' }}>Current Humidity</h4>
                <h2 style={{ margin: '10px 0 0 0', color: '#3b82f6', fontSize: '36px' }}>{latestData.humidity}%</h2>
              </div>
            </div>

            {/* 📈 রিয়েল-টাইম গ্রাফিক্যাল চার্ট */}
            <div style={{ width: '100%', height: 400 }}>
              <ResponsiveContainer>
                <LineChart data={chartData}>
                  <CartesianGrid strokeDasharray="3 3" stroke="#eee" />
                  <XAxis dataKey="id" />
                  <YAxis />
                  <Tooltip />
                  <Legend />
                  <Line type="monotone" dataKey="temperature" name="Temperature (°C)" stroke="#ef4444" strokeWidth={3} animationDuration={500} />
                  <Line type="monotone" dataKey="humidity" name="Humidity (%)" stroke="#3b82f6" strokeWidth={3} animationDuration={500} />
                </LineChart>
              </ResponsiveContainer>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

export default App;
