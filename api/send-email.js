const nodemailer = require('nodemailer');

// Create a transporter object using Gmail SMTP
const transporter = nodemailer.createTransport({
  service: 'gmail',
  auth: {
    user: process.env.EMAIL_USER,
    pass: process.env.EMAIL_PASS
  }
});

module.exports = async (req, res) => {
  // Only allow POST requests
  if (req.method !== 'POST') {
    return res.status(405).json({ error: 'Method not allowed' });
  }

  try {
    const { name, email, subject, message } = req.body;

    // Basic validation
    if (!name || !email || !subject || !message) {
      return res.status(400).json({ error: 'All fields are required' });
    }

    // Email options
    const mailOptions = {
      from: `${name} <${process.env.EMAIL_USER}>`,
      to: process.env.RECIPIENT_EMAIL || 'assignmenthelp6435@gmail.com',
      subject: `Re: ${subject}`,
      html: `
        <!DOCTYPE html>
        <html>
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; }
            .header { background-color: #2c3e50; padding: 20px; color: white; }
            .content { padding: 20px; }
            .message { 
              background-color: #f8f9fa; 
              padding: 20px; 
              border-left: 4px solid #3498db;
              margin: 20px 0;
              border-radius: 4px;
            }
            .footer { 
              margin-top: 30px;
              padding-top: 20px;
              border-top: 1px solid #eee;
              color: #777;
              font-size: 0.9em;
            }
            .details { 
              background-color: #f5f7fa;
              padding: 15px;
              border-radius: 4px;
              margin: 15px 0;
            }
          </style>
        </head>
        <body>
          <div class='header'>
            <h1 style='margin: 0; color: white;'>New Website Inquiry</h1>
            <p style='margin: 5px 0 0 0; color: #ecf0f1; font-size: 0.9em;'>${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
          </div>
          
          <div class='content'>
            <h2 style='color: #2c3e50; margin-top: 0;'>${subject}</h2>
            
            <div class='details'>
              <p style='margin: 5px 0;'><strong>From:</strong> ${name}</p>
              <p style='margin: 5px 0;'><strong>Email:</strong> ${email}</p>
              <p style='margin: 5px 0;'><strong>Date:</strong> ${new Date().toLocaleString()}</p>
            </div>
            
            <h3 style='color: #2c3e50;'>Message:</h3>
            <div class='message'>
              ${message.replace(/\n/g, '<br>')}
            </div>
            
            <div class='footer'>
              <p>This is an automated message from your website contact form. Please do not reply directly to this email.</p>
              <p>&copy; ${new Date().getFullYear()} Your Company Name. All rights reserved.</p>
            </div>
          </div>
        </body>
        </html>
      `,
      text: `
        NEW WEBSITE INQUIRY
        ${'='.repeat(50)}
        
        Subject: ${subject}
        Date: ${new Date().toLocaleString()}
        From: ${name} <${email}>
        
        ${'-'.repeat(50)}
        MESSAGE:
        ${'-'.repeat(50)}
        ${message}
        
        ${'-'.repeat(50)}
        This is an automated message from your website contact form.
      `
    };

    // Send email
    await transporter.sendMail(mailOptions);
    
    res.status(200).json({ message: 'Message sent successfully' });
  } catch (error) {
    console.error('Error sending email:', error);
    res.status(500).json({ 
      error: 'Failed to send message',
      details: process.env.NODE_ENV === 'development' ? error.message : undefined
    });
  }
};
