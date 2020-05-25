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
    convenience init(redd:CGFloat,greenn:CGFloat,bluee:CGFloat,alphaa:CGFloat)
    {
        self.init(red: redd/255, green: greenn/255, blue: bluee/255,alpha: alphaa)
    }
}
//MARK:- UIViewController Extension

var vSpinner : UIView?

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
}

extension UIColor {
    static let cardBgColor = UIColor(redd: 235.0, greenn: 235.0, bluee: 235.0, alphaa: 1.0)
    static let spinnerBackgroundColor = UIColor(redd: 192.0, greenn: 192.0, bluee: 192.0, alphaa: 0.5)
    static let firstGradientColor = UIColor(redd: 0.0, greenn: 4.0, bluee: 40.0, alphaa: 1.0)
    static let secondGradientColor = UIColor(redd: 0.0, greenn: 78.0, bluee: 146.0, alphaa: 1.0)
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
/// for got the device model name
public extension UIDevice {

    static let modelName: String = {
        var systemInfo = utsname()
        uname(&systemInfo)
        let machineMirror = Mirror(reflecting: systemInfo.machine)
        let identifier = machineMirror.children.reduce("") { identifier, element in
            guard let value = element.value as? Int8, value != 0 else { return identifier }
            return identifier + String(UnicodeScalar(UInt8(value)))
        }

        func mapToDevice(identifier: String) -> String { // swiftlint:disable:this cyclomatic_complexity
            #if os(iOS)
            switch identifier {
            case "iPod5,1":                                 return "iPod touch (5th generation)"
            case "iPod7,1":                                 return "iPod touch (6th generation)"
            case "iPod9,1":                                 return "iPod touch (7th generation)"
            case "iPhone3,1", "iPhone3,2", "iPhone3,3":     return "iPhone 4"
            case "iPhone4,1":                               return "iPhone 4s"
            case "iPhone5,1", "iPhone5,2":                  return "iPhone 5"
            case "iPhone5,3", "iPhone5,4":                  return "iPhone 5c"
            case "iPhone6,1", "iPhone6,2":                  return "iPhone 5s"
            case "iPhone7,2":                               return "iPhone 6"
            case "iPhone7,1":                               return "iPhone 6 Plus"
            case "iPhone8,1":                               return "iPhone 6s"
            case "iPhone8,2":                               return "iPhone 6s Plus"
            case "iPhone8,4":                               return "iPhone SE"
            case "iPhone9,1", "iPhone9,3":                  return "iPhone 7"
            case "iPhone9,2", "iPhone9,4":                  return "iPhone 7 Plus"
            case "iPhone10,1", "iPhone10,4":                return "iPhone 8"
            case "iPhone10,2", "iPhone10,5":                return "iPhone 8 Plus"
            case "iPhone10,3", "iPhone10,6":                return "iPhone X"
            case "iPhone11,2":                              return "iPhone XS"
            case "iPhone11,4", "iPhone11,6":                return "iPhone XS Max"
            case "iPhone11,8":                              return "iPhone XR"
            case "iPhone12,1":                              return "iPhone 11"
            case "iPhone12,3":                              return "iPhone 11 Pro"
            case "iPhone12,5":                              return "iPhone 11 Pro Max"
            case "iPhone12,8":                              return "iPhone SE (2nd generation)"
            case "iPad2,1", "iPad2,2", "iPad2,3", "iPad2,4":return "iPad 2"
            case "iPad3,1", "iPad3,2", "iPad3,3":           return "iPad (3rd generation)"
            case "iPad3,4", "iPad3,5", "iPad3,6":           return "iPad (4th generation)"
            case "iPad6,11", "iPad6,12":                    return "iPad (5th generation)"
            case "iPad7,5", "iPad7,6":                      return "iPad (6th generation)"
            case "iPad7,11", "iPad7,12":                    return "iPad (7th generation)"
            case "iPad4,1", "iPad4,2", "iPad4,3":           return "iPad Air"
            case "iPad5,3", "iPad5,4":                      return "iPad Air 2"
            case "iPad11,4", "iPad11,5":                    return "iPad Air (3rd generation)"
            case "iPad2,5", "iPad2,6", "iPad2,7":           return "iPad mini"
            case "iPad4,4", "iPad4,5", "iPad4,6":           return "iPad mini 2"
            case "iPad4,7", "iPad4,8", "iPad4,9":           return "iPad mini 3"
            case "iPad5,1", "iPad5,2":                      return "iPad mini 4"
            case "iPad11,1", "iPad11,2":                    return "iPad mini (5th generation)"
            case "iPad6,3", "iPad6,4":                      return "iPad Pro (9.7-inch)"
            case "iPad7,3", "iPad7,4":                      return "iPad Pro (10.5-inch)"
            case "iPad8,1", "iPad8,2", "iPad8,3", "iPad8,4":return "iPad Pro (11-inch)"
            case "iPad8,9", "iPad8,10":                     return "iPad Pro (11-inch) (2nd generation)"
            case "iPad6,7", "iPad6,8":                      return "iPad Pro (12.9-inch)"
            case "iPad7,1", "iPad7,2":                      return "iPad Pro (12.9-inch) (2nd generation)"
            case "iPad8,5", "iPad8,6", "iPad8,7", "iPad8,8":return "iPad Pro (12.9-inch) (3rd generation)"
            case "iPad8,11", "iPad8,12":                    return "iPad Pro (12.9-inch) (4th generation)"
            case "AppleTV5,3":                              return "Apple TV"
            case "AppleTV6,2":                              return "Apple TV 4K"
            case "AudioAccessory1,1":                       return "HomePod"
            case "i386", "x86_64":                          return "Simulator \(mapToDevice(identifier: ProcessInfo().environment["SIMULATOR_MODEL_IDENTIFIER"] ?? "iOS"))"
            default:                                        return identifier
            }
            #elseif os(tvOS)
            switch identifier {
            case "AppleTV5,3": return "Apple TV 4"
            case "AppleTV6,2": return "Apple TV 4K"
            case "i386", "x86_64": return "Simulator \(mapToDevice(identifier: ProcessInfo().environment["SIMULATOR_MODEL_IDENTIFIER"] ?? "tvOS"))"
            default: return identifier
            }
            #endif
        }

        return mapToDevice(identifier: identifier)
    }()

}

extension UIButton
{
    /// for the EPG menu categories
     func setBtnProperties(_ button:UIButton)
    {
        button.setTitleColor(UIColor.white, for: .focused)
        button.setTitleColor(UIColor.white, for: .highlighted)
        button.setBackgroundImage(UIImage.imageWithColor(.clear), for: .normal)
        button.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: button.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        button.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: button.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
    }
}
