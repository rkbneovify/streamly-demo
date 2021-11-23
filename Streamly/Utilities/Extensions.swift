//
//  Extensions.swift
//  Streamly
//
//  Created by Rohit Singh on 5/13/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

extension UIView {

    //IBInspectable
    @IBInspectable var cornerRadius: Double {
        get {
            return Double(self.layer.cornerRadius)
        }set {
            self.layer.cornerRadius = CGFloat(newValue)
        }
    }
    @IBInspectable var borderWidth: Double {
        get {
            return Double(self.layer.borderWidth)
        }
        set {
            self.layer.borderWidth = CGFloat(newValue)
        }
    }
    @IBInspectable var borderColor: UIColor? {
        get {
            return UIColor(cgColor: self.layer.borderColor!)
        }
        set {
            self.layer.borderColor = newValue?.cgColor
        }
    }
    @IBInspectable var shadowColor: UIColor? {
        get {
            return UIColor(cgColor: self.layer.shadowColor!)
        }
        set {
            self.layer.shadowColor = newValue?.cgColor
        }
    }
    @IBInspectable var shadowOpacity: Float {
        get {
            return self.layer.shadowOpacity
        }
        set {
            self.layer.shadowOpacity = newValue
        }
    }
}




//MARK: UIcolor extension
extension UIColor
{
    convenience init(redd:CGFloat,greenn:CGFloat,bluee:CGFloat,alphaa:CGFloat)
    {
        self.init(red: redd/255, green: greenn/255, blue: bluee/255,alpha: alphaa)
    }
}
//MARK: UIViewController Extension

var vSpinner : UIView?

extension UIViewController
{
    
    func showToast(message : String, completion: @escaping (() -> Void)) {
        DispatchQueue.main.async {
            let alert = UIAlertController(title: appName, message: message, preferredStyle: .alert)
            self.present(alert, animated: true, completion: nil)
            
            DispatchQueue.main.asyncAfter(deadline: .now() + 2.0) {
                alert.dismiss(animated: true, completion: nil)
                completion()
            }
        }
       
    }

  
  func showAlert(withMessage message: String, setTwoButton: Bool? = false , setFirstButtonTitle: String? = "Ok" , setSecondButtonTitle: String? = "No" , handler: ((UIAlertAction) -> Void)?) {

        let alert = UIAlertController(title: appName, message: message, preferredStyle: .alert)

        if (setTwoButton == true) {
            alert.addAction(UIAlertAction(title: setSecondButtonTitle, style: .default, handler:nil))
        }
        alert.addAction(UIAlertAction(title: setFirstButtonTitle, style: .default, handler:{ (action) in
            handler!(action)
        }))
        self.present(alert, animated: true, completion: nil)
    }
    
    func showSimpleAlertWithMessage(_ message: String) {
        DispatchQueue.main.async {
            let alert = UIAlertController(title: appName, message: message, preferredStyle: .alert)
            let okAction = UIAlertAction(title: "Ok", style: .default, handler: nil)
            alert.addAction(okAction)
            self.present(alert, animated: true, completion: nil)
        }
    }

    class var storyboardID : String {
        return "\(self)"
    }

    static func instantiateFromAppStoryboard(appStoryboard: AppStoryboard) -> Self {
        return appStoryboard.viewController(viewControllerClass: self)
    }
    func showSpinner(onView : UIView) {
        let spinnerView = UIView.init(frame: onView.bounds)
        spinnerView.backgroundColor = .spinnerBackgroundColor
        let aIndi = UIActivityIndicatorView.init(style: .large)
        aIndi.startAnimating()
        aIndi.center = spinnerView.center

        DispatchQueue.main.async {
            spinnerView.addSubview(aIndi)
            onView.addSubview(spinnerView)
        }

        vSpinner = spinnerView
    }

    func removeSpinner() {
        DispatchQueue.main.async {
            vSpinner?.removeFromSuperview()
            vSpinner = nil
        }
    }

    func validateEmail(text: String) -> String {
        if text.isEmptyString() {
            return ValidationErrorMessages.emailEmpty.descriptipon()
        } else if !text.isValidEmailID() {

            return  ValidationErrorMessages.emailInvalid.descriptipon()
        } else {
            return ""
        }
    }



    func validatePassword(text: String) -> String {
        if text.isEmptyString() {
            return ValidationErrorMessages.passwordEmpty.descriptipon()
        } else if !text.isValidPassword() {
            return ValidationErrorMessages.passwordInvalid.descriptipon()
        } else {
            return ""
        }
    }
}
//MARK:- String extension
extension String
{
    func toDate() -> Date? {
        let formatter = DateFormatter()
        formatter.dateFormat = "dd/MM/yyyy hh:mm a"
        return formatter.date(from: self)
    }

    func isEmptyString() -> Bool {
        let trimmedString = self.components(separatedBy: .whitespacesAndNewlines).joined(separator: "")
        return trimmedString.isEmpty
    }

    func isValidEmailID() ->Bool {
        if self.isEmpty {
            return false
        }
        let emailRegEx = "[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}"

        let emailTest = NSPredicate(format:"SELF MATCHES %@", emailRegEx)
        return emailTest.evaluate(with: self)
    }

    func isValidPhoneNumber() -> Bool {
        let phoneregEx = "^[0-9]{8,15}"
        let phoneTest = NSPredicate(format: "SELF MATCHES %@", phoneregEx)
        let result =  phoneTest.evaluate(with: self)
        return result
    }

    func isValidPassword() -> Bool {
        if self.isEmptyString() || self.count < passwordMinLimit || self.count > passwordMaxLimit {
            return false
        }
        return true
    }

    ///for load HTML string in TextView
    var htmlToAttributedString: NSAttributedString? {
        guard let data = data(using: .utf8) else { return NSAttributedString() }
        do {
            return try NSAttributedString(data: data, options: [.documentType: NSAttributedString.DocumentType.html, .characterEncoding:String.Encoding.utf8.rawValue], documentAttributes: nil)
        } catch {
            return NSAttributedString()
        }
    }
    var htmlToString: String {
        return htmlToAttributedString?.string ?? ""
    }
}

extension UIColor {

    static let cardBgColor = UIColor(redd: 235.0, greenn: 235.0, bluee: 235.0, alphaa: 1.0)
    static let spinnerBackgroundColor = UIColor(redd: 192.0, greenn: 192.0, bluee: 192.0, alphaa: 0.5)
    static let firstGradientColor = UIColor(redd: 0.0, greenn: 4.0, bluee: 40.0, alphaa: 1.0)
    static let secondGradientColor = UIColor(redd: 0.0, greenn: 78.0, bluee: 146.0, alphaa: 1.0)
    static let channelBox = UIColor(redd: 60.0, greenn: 60.0, bluee: 67.0, alphaa: 0.2)
    static let categorieBgColor = UIColor(redd: 143.0, greenn: 179.0, bluee: 143.0, alphaa: 1.0)

    public convenience init?(hex: String) {
        let r, g, b, a: CGFloat

        if hex.hasPrefix("#") {
            let start = hex.index(hex.startIndex, offsetBy: 1)
            let hexColor = String(hex[start...])

            if hexColor.count == 8 {
                let scanner = Scanner(string: hexColor)
                var hexNumber: UInt64 = 0

                if scanner.scanHexInt64(&hexNumber) {
                    r = CGFloat((hexNumber & 0xff000000) >> 24) / 255
                    g = CGFloat((hexNumber & 0x00ff0000) >> 16) / 255
                    b = CGFloat((hexNumber & 0x0000ff00) >> 8) / 255
                    a = CGFloat(hexNumber & 0x000000ff) / 255

                    self.init(red: r, green: g, blue: b, alpha: a)
                    return
                }
            }
        }

        return nil
    }
}

public extension UIImage {
    static func imageWithColor(_ color : UIColor) -> UIImage {
        let rect = CGRect(x: 0.0, y: 0.0, width: 1.0, height: 1.0)
        UIGraphicsBeginImageContext(rect.size)
        let context = UIGraphicsGetCurrentContext()

        context!.setFillColor(color.cgColor)
        context!.fill(rect)

        let image = UIGraphicsGetImageFromCurrentImageContext()
        UIGraphicsEndImageContext()

        return image!
    }

    static func gradientImageWithBounds(bounds: CGRect, colors: [CGColor]) -> UIImage {
        let gradientLayer = CAGradientLayer()
        gradientLayer.frame = bounds
        gradientLayer.colors = colors
        gradientLayer.startPoint = CGPoint(x: 0, y: 0.5)
        gradientLayer.endPoint = CGPoint (x: 1, y: 0.5)
        UIGraphicsBeginImageContext(gradientLayer.bounds.size)
        gradientLayer.render(in: UIGraphicsGetCurrentContext()!)
        let image = UIGraphicsGetImageFromCurrentImageContext()
        UIGraphicsEndImageContext()
        return image!
    }

}

extension UIButton
{
    /// for the EPG menu categories
    func setBtnProperties(_ button:UIButton,_ flag:Bool)
    {
        button.setTitleColor(UIColor.white, for: .focused)
        button.setTitleColor(UIColor.white, for: .highlighted)
        if flag {
            button.setTitleColor(UIColor.black, for: .normal)
        }
        button.setBackgroundImage(UIImage.imageWithColor(.clear), for: .normal)
        button.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: button.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        button.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: button.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
    }
}
extension UIView {
    
    /** Loads instance from nib with the same name. */
    func loadNib() -> UIView {
        let bundle = Bundle(for: type(of: self))
        let nibName = type(of: self).description().components(separatedBy: ".").last!
        let nib = UINib(nibName: nibName, bundle: bundle)
        //debugPrint(nibName)
        return nib.instantiate(withOwner: self, options: nil).first as! UIView
    }
}

extension UITableView {

    func setEmptyMessage(_ message: String) {
        let messageLabel = UILabel(frame: CGRect(x: 0, y: 0, width: self.bounds.size.width, height: self.bounds.size.height))
        messageLabel.text = message
        messageLabel.textColor = .white
        messageLabel.numberOfLines = 0
        messageLabel.textAlignment = .center
        messageLabel.font = UIFont(name: "HelveticaNeue-Medium", size: 30)
        messageLabel.sizeToFit()
        
        self.backgroundView = messageLabel

    }

    func restore() {
        self.backgroundView = nil

    }
}
extension UICollectionView {

    func setEmptyMessage(_ message: String) {
        let messageLabel = UILabel(frame: CGRect(x: 0, y: 0, width: self.bounds.size.width, height: self.bounds.size.height))
        messageLabel.text = message
        messageLabel.textColor = .white
        messageLabel.numberOfLines = 0
        messageLabel.textAlignment = .center
        messageLabel.font = UIFont(name: "HelveticaNeue-Medium", size: 30)
        messageLabel.sizeToFit()

        self.backgroundView = messageLabel

    }

    func restore() {
        self.backgroundView = nil

    }
}

extension TimeZone {
    static let gmt = TimeZone(secondsFromGMT: 0)!
}
extension Formatter {
    static let date = DateFormatter()
}

extension Date {
    func string(format: String) -> String {
        let formatter = DateFormatter()
        formatter.dateFormat = format
        return formatter.string(from: self)
    }

    static var yesterday: Date { return Date().dayBefore }
       static var tomorrow:  Date { return Date().dayAfter }
       var dayBefore: Date {
           return Calendar.current.date(byAdding: .day, value: -1, to: self)!
       }
       var dayAfter: Date {
           return Calendar.current.date(byAdding: .day, value: 1, to: self)!
       }
       var noon: Date {
           return Calendar.current.date(bySettingHour: 0, minute: 0, second: 0, of: self)!
       }
       var month: Int {
           return Calendar.current.component(.month,  from: self)
       }
       var isLastDayOfMonth: Bool {
           return dayAfter.month != month
       }

}

extension UIView {
    func screenshot() -> UIImage {
        return UIGraphicsImageRenderer(size: bounds.size).image { _ in
            drawHierarchy(in: CGRect(origin: .zero, size: bounds.size), afterScreenUpdates: true)
        }
    }
}
public extension CGFloat {
    func pointsToPixel() -> CGFloat {
        return self * UIScreen.main.scale
    }

}
extension UIImage {

func alpha(_ value:CGFloat) -> UIImage {
    UIGraphicsBeginImageContextWithOptions(size, false, scale)
    draw(at: CGPoint.zero, blendMode: .normal, alpha: value)
    let newImage = UIGraphicsGetImageFromCurrentImageContext()
    UIGraphicsEndImageContext()
    return newImage!

    }}
