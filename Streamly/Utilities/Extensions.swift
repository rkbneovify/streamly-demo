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




//MARK:- UIcolor extension
extension UIColor
{
  convenience init(r:CGFloat,g:CGFloat,b:CGFloat,a:CGFloat)
    {
        self.init(red: r/255, green: g/255, blue: b/255,alpha: a)
    }
}
//MARK:- UIViewController Extension
extension UIViewController
{
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

}
//Mark:- String extension
extension String
{
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
        let PHONE_REGEX = "^[0-9]{8,15}"
        let phoneTest = NSPredicate(format: "SELF MATCHES %@", PHONE_REGEX)
        let result =  phoneTest.evaluate(with: self)
        return result
    }

    func isValidPassword() -> Bool {
        if self.isEmptyString() || self.count < password_min_limit || self.count > password_max_limit {
            return false
        }
        return true
    }
}
